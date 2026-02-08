<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Security;
use App\Repositories\ParceiroRepository;
use PDO;

class ConsignadoController extends Controller
{
    private ParceiroRepository $repo;
    private PDO $db;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
        $this->repo = new ParceiroRepository();
        $this->db = Database::getConnection();
    }

    public function index(): void
    {
        $parceiros = $this->repo->allComResumo();
        $resumo = $this->repo->resumoGeral();

        $this->layout('layouts/painel', 'admin/consignado/index', [
            'parceiros' => $parceiros,
            'resumo' => $resumo,
        ]);
    }

    public function parceiro($id): void
    {
        $id = (int)$id;
        $parceiro = $this->repo->findComResumo($id);
        if (!$parceiro) {
            http_response_code(404);
            exit('Parceiro não encontrado');
        }
        $produtos = $this->repo->produtos($id);
        $movimentacoes = $this->repo->movimentacoesConsignado($id, 100);
        $produtosLoja = $this->carregarProdutosLoja();

        $this->layout('layouts/painel', 'admin/consignado/show', [
            'parceiro' => $parceiro,
            'produtos' => $produtos,
            'movimentacoes' => $movimentacoes,
            'produtosLoja' => $produtosLoja,
        ]);
    }

    public function transferir(): void
    {
        $this->validateCsrf();
        $parceiroId = (int)($_POST['parceiro_id'] ?? 0);
        $produtoId = (int)($_POST['produto_id'] ?? 0);
        $produtoNome = Security::sanitizeString($_POST['produto_nome'] ?? '');
        $quantidade = (int)($_POST['quantidade'] ?? 0);
        $lote = Security::sanitizeString($_POST['lote'] ?? '');
        $nf = Security::sanitizeString($_POST['nf'] ?? '');
        $prazoDev = Security::sanitizeString($_POST['prazo_dev'] ?? '');

        if ($parceiroId <= 0 || ($produtoId <= 0 && !$produtoNome) || $quantidade <= 0) {
            http_response_code(400);
            exit('Dados inválidos para transferência');
        }

        $produto = $produtoId > 0 ? $this->buscarProduto($produtoId) : $this->buscarProdutoPorNome($produtoNome);
        if (!$produto) {
            http_response_code(404);
            exit('Produto não encontrado');
        }

        $this->db->beginTransaction();
        try {
            $row = $this->buscarConsignadoProduto($parceiroId, $produto['nome']);
            if ($row) {
                $stmt = $this->db->prepare(
                    "UPDATE " . Database::table('consignado_produtos') . "
                     SET estoque = estoque + :qtd,
                         minimo = :minimo,
                         categoria = :categoria,
                         sku = :sku,
                         lote = COALESCE(:lote, lote),
                         nf = COALESCE(:nf, nf),
                         prazo_dev = COALESCE(:prazo_dev, prazo_dev)
                     WHERE parceiro_id = :parceiro_id AND produto = :produto"
                );
                $stmt->execute([
                    'qtd' => $quantidade,
                    'minimo' => (int)($produto['minimo'] ?? 0),
                    'categoria' => $produto['categoria'] ?? '',
                    'sku' => $produto['sku'] ?? '',
                    'lote' => $lote ?: null,
                    'nf' => $nf ?: null,
                    'prazo_dev' => $prazoDev ?: null,
                    'parceiro_id' => $parceiroId,
                    'produto' => $produto['nome'],
                ]);
            } else {
                $stmt = $this->db->prepare(
                    "INSERT INTO " . Database::table('consignado_produtos') . "
                     (parceiro_id, produto, sku, categoria, lote, nf, estoque, minimo, vendido_mes, devolucao, prazo_dev)
                     VALUES (:parceiro_id, :produto, :sku, :categoria, :lote, :nf, :estoque, :minimo, 0, 0, :prazo_dev)"
                );
                $stmt->execute([
                    'parceiro_id' => $parceiroId,
                    'produto' => $produto['nome'],
                    'sku' => $produto['sku'] ?? '',
                    'categoria' => $produto['categoria'] ?? '',
                    'lote' => $lote ?: null,
                    'nf' => $nf ?: null,
                    'estoque' => $quantidade,
                    'minimo' => (int)($produto['minimo'] ?? 0),
                    'prazo_dev' => $prazoDev ?: null,
                ]);
            }

            $this->registrarMov($parceiroId, 'transferencia', "Transferência de loja", $produto['nome'], $quantidade);

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        $this->redirect('/admin/consignado/parceiro/' . $parceiroId . '/ver?ok=1');
    }

    public function devolver(): void
    {
        $this->validateCsrf();
        $parceiroId = (int)($_POST['parceiro_id'] ?? 0);
        $produtoId = (int)($_POST['produto_id'] ?? 0);
        $produtoNome = Security::sanitizeString($_POST['produto_nome'] ?? '');
        $quantidade = (int)($_POST['quantidade'] ?? 0);
        if ($parceiroId <= 0 || ($produtoId <= 0 && !$produtoNome) || $quantidade <= 0) {
            http_response_code(400);
            exit('Dados inválidos para devolução');
        }

        $produto = $produtoId > 0 ? $this->buscarProduto($produtoId) : $this->buscarProdutoPorNome($produtoNome);
        if (!$produto) {
            http_response_code(404);
            exit('Produto não encontrado');
        }

        $this->db->beginTransaction();
        try {
            $row = $this->buscarConsignadoProduto($parceiroId, $produto['nome']);
            if ($row) {
                $novoEstoque = max(0, ((int)$row['estoque']) - $quantidade);
                $stmt = $this->db->prepare(
                    "UPDATE " . Database::table('consignado_produtos') . "
                     SET estoque = :estoque, devolucao = devolucao + :qtd
                     WHERE parceiro_id = :parceiro_id AND produto = :produto"
                );
                $stmt->execute([
                    'estoque' => $novoEstoque,
                    'qtd' => $quantidade,
                    'parceiro_id' => $parceiroId,
                    'produto' => $produto['nome'],
                ]);
            }

            $this->registrarMov($parceiroId, 'devolucao', 'Devolução para loja', $produto['nome'], $quantidade);
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        $this->redirect('/admin/consignado/parceiro/' . $parceiroId . '/ver?ok=1');
    }

    private function buscarProduto(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nome, sku, categoria, minimo FROM " . Database::table('produtos') . " WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function buscarProdutoPorNome(string $nome): ?array
    {
        if ($nome === '') {
            return null;
        }
        $stmt = $this->db->prepare("SELECT id, nome, sku, categoria, minimo FROM " . Database::table('produtos') . " WHERE nome = :nome LIMIT 1");
        $stmt->execute(['nome' => $nome]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function buscarConsignadoProduto(int $parceiroId, string $nome): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM " . Database::table('consignado_produtos') . " WHERE parceiro_id = :pid AND produto = :nome LIMIT 1"
        );
        $stmt->execute(['pid' => $parceiroId, 'nome' => $nome]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function registrarMov(int $parceiroId, string $tipo, string $descricao, string $produto, int $quantidade): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('consignado_movimentacoes') . " (parceiro_id, tipo, descricao, produto, quantidade, data, usuario)
             VALUES (:parceiro_id, :tipo, :descricao, :produto, :quantidade, :data, :usuario)"
        );
        $stmt->execute([
            'parceiro_id' => $parceiroId,
            'tipo' => $tipo,
            'descricao' => $descricao,
            'produto' => $produto,
            'quantidade' => $quantidade,
            'data' => date('Y-m-d H:i:s'),
            'usuario' => $_SESSION['user']['nome'] ?? 'Sistema',
        ]);
    }

    private function carregarProdutosLoja(): array
    {
        $stmt = $this->db->query("SELECT id, nome, sku FROM " . Database::table('produtos') . " ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
