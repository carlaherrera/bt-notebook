<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Repositories\ParceiroRepository;
use App\Repositories\UserRepository;
use App\Core\Security;
use App\Core\Database;

class ParceirosController extends Controller
{
    private ParceiroRepository $repo;
    private UserRepository $users;
    private $db;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
        $this->repo = new ParceiroRepository();
        $this->users = new UserRepository();
        $this->db = Database::getConnection();
    }

    public function create(): void
    {
        $this->layout('layouts/painel', 'admin/parceiros/novo', []);
    }

    public function index(): void
    {
        $parceiros = $this->repo->allComResumo();

        $resumo = [
            'ativos' => count(array_filter($parceiros, fn($p) => ($p['status'] ?? '') === 'ativo')),
            'inativos' => count(array_filter($parceiros, fn($p) => ($p['status'] ?? '') !== 'ativo')),
            'itens_total' => array_sum(array_column($parceiros, 'itens')),
            'parceiros' => count($parceiros),
            'estoque_baixo' => array_sum(array_column($parceiros, 'baixo')),
            'vendas_mes' => array_sum(array_column($parceiros, 'vendas_mes')),
        ];

        $this->layout('layouts/painel', 'admin/parceiros/index', [
            'parceiros' => $parceiros,
            'resumo' => $resumo,
        ]);
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $parceiro = $this->repo->findComResumo($id);
        if (!$parceiro) {
            http_response_code(404);
            exit('Parceiro não encontrado');
        }

        $this->layout('layouts/painel', 'admin/parceiros/editar', [
            'parceiro' => $parceiro,
        ]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $parceiro = $this->repo->findComResumo($id);
        if (!$parceiro) {
            http_response_code(404);
            exit('Parceiro não encontrado');
        }

        $nome = Security::sanitizeString($_POST['nome'] ?? '');
        $sobrenome = Security::sanitizeString($_POST['sobrenome'] ?? '');
        $email = Security::sanitizeString($_POST['email'] ?? '');
        $statusAtivo = isset($_POST['status']) ? 1 : 0;

        $tipo = Security::sanitizeString($_POST['tipo'] ?? 'academia');
        $documento = Security::sanitizeString($_POST['documento'] ?? '');
        $cidade = Security::sanitizeString($_POST['cidade'] ?? '');
        $contato = Security::sanitizeString($_POST['contato'] ?? '');
        $telefone = Security::sanitizeString($_POST['telefone'] ?? '');
        $ticket = (float)($_POST['ticket_medio'] ?? 0);

        // Atualiza usuário vinculado
        if ($parceiro['usuario_id'] ?? null) {
            $this->users->update((int)$parceiro['usuario_id'], [
                'nome' => $nome,
                'sobrenome' => $sobrenome,
                'email' => $email,
                'status' => $statusAtivo,
            ]);
        }

        // Atualiza parceiro
        $stmt = $this->db->prepare(
            "UPDATE " . Database::table('parceiros') . "
             SET nome = :nome, tipo = :tipo, documento = :documento, cidade = :cidade,
                 contato = :contato, telefone = :telefone, email = :email, status = :status,
                 ticket_medio = :ticket_medio, atualizado_em = :atualizado_em
             WHERE id = :id"
        );
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'tipo' => $tipo,
            'documento' => $documento,
            'cidade' => $cidade,
            'contato' => $contato,
            'telefone' => $telefone,
            'email' => $email,
            'status' => $statusAtivo ? 'ativo' : 'inativo',
            'ticket_medio' => $ticket,
            'atualizado_em' => date('Y-m-d H:i:s'),
        ]);

        $this->redirect('/admin/parceiros/' . $id . '/ver');
    }

    public function toggle($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $parceiro = $this->repo->findComResumo($id);
        if (!$parceiro) {
            http_response_code(404);
            exit('Parceiro não encontrado');
        }

        $novoStatus = strtolower((string)($parceiro['status'] ?? '')) === 'ativo' ? 'inativo' : 'ativo';
        $stmt = $this->db->prepare("UPDATE " . Database::table('parceiros') . " SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $novoStatus, 'id' => $id]);

        if ($parceiro['usuario_id'] ?? null) {
            $this->users->update((int)$parceiro['usuario_id'], ['status' => $novoStatus === 'ativo' ? 1 : 0]);
        }

        $this->redirect('/admin/parceiros/' . $id . '/ver');
    }

    public function store(): void
    {
        $this->validateCsrf();

        $nome = Security::sanitizeString($_POST['nome'] ?? '');
        $sobrenome = Security::sanitizeString($_POST['sobrenome'] ?? '');
        $email = Security::sanitizeString($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $senhaConfirm = $_POST['senha_confirmacao'] ?? '';
        $status = isset($_POST['status']) ? 'ativo' : 'inativo';

        $tipo = Security::sanitizeString($_POST['tipo'] ?? 'academia');
        $documento = Security::sanitizeString($_POST['documento'] ?? '');
        $cidade = Security::sanitizeString($_POST['cidade'] ?? '');
        $contato = Security::sanitizeString($_POST['contato'] ?? '');
        $telefone = Security::sanitizeString($_POST['telefone'] ?? '');
        $ticket = (float)($_POST['ticket_medio'] ?? 0);

        if (!$nome || !$email || !$senha || $senha !== $senhaConfirm) {
            http_response_code(400);
            exit('Dados inválidos ou senhas não conferem.');
        }

        if ($this->users->findByEmail($email)) {
            http_response_code(400);
            exit('E-mail já cadastrado.');
        }

        // Cria usuário role parceiro
        $userId = $this->users->insert([
            'nome' => $nome,
            'sobrenome' => $sobrenome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_BCRYPT),
            'role' => 'parceiro',
            'status' => 1,
        ]);

        // Cria registro de parceiro
        $stmt = $this->db->prepare(
            "INSERT INTO " . Database::table('parceiros') . "
            (nome, tipo, documento, cidade, contato, telefone, email, status, ticket_medio, atualizado_em, usuario_id)
            VALUES (:nome, :tipo, :documento, :cidade, :contato, :telefone, :email, :status, :ticket_medio, :atualizado_em, :usuario_id)"
        );
        $stmt->execute([
            'nome' => $nome,
            'tipo' => $tipo ?: 'academia',
            'documento' => $documento,
            'cidade' => $cidade,
            'contato' => $contato,
            'telefone' => $telefone,
            'email' => $email,
            'status' => $status,
            'ticket_medio' => $ticket,
            'atualizado_em' => date('Y-m-d H:i:s'),
            'usuario_id' => $userId,
        ]);

        $parceiroId = (int)$this->db->lastInsertId();
        $this->redirect('/admin/parceiros/' . $parceiroId . '/ver');
    }

    public function show($id): void
    {
        $id = (int)$id;
        \App\Core\Logger::debug('ParceirosController::show iniciado', ['id' => $id]);
        
        $parceiro = $this->repo->findComResumo($id);

        if (!$parceiro) {
            \App\Core\Logger::warning('Parceiro não encontrado', ['id' => $id]);
            http_response_code(404);
            exit('Parceiro não encontrado');
        }
        
        \App\Core\Logger::debug('Parceiro encontrado', ['id' => $id, 'parceiro' => $parceiro['nome'] ?? 'sem nome']);

        $consignado = $this->repo->produtos($id);
        $movimentacoes = $this->repo->movimentacoesConsignado($id, 100);

        $insights = [
            'itens' => array_sum(array_column($consignado, 'estoque')),
            'itens_baixo' => count(array_filter($consignado, fn($c) => ($c['estoque'] ?? 0) <= ($c['min'] ?? 0))),
            'vendas_mes' => array_sum(array_column($consignado, 'vendido_mes')),
            'devolucao' => array_sum(array_column($consignado, 'devolucao')),
        ];

        $this->layout('layouts/painel', 'admin/parceiros/show', [
            'parceiro' => $parceiro,
            'consignado' => $consignado,
            'movimentacoes' => $movimentacoes,
            'insights' => $insights,
        ]);
    }

    public function relatorio($id): void
    {
        $id = (int)$id;
        $parceiro = $this->repo->findComResumo($id);

        if (!$parceiro) {
            http_response_code(404);
            exit('Parceiro não encontrado');
        }

        $periodo = ['inicio' => '2026-01-01', 'fim' => '2026-01-31'];
        // Dados iniciais usando movimentações reais para alimentar listas e totais simples
        $movimentacoes = $this->repo->movimentacoesConsignado($id, 200);

        $totais = [
            'transferencias' => array_sum(array_map(fn($m) => ($m['tipo'] ?? '') === 'transferencia' ? (int)($m['quantidade'] ?? 0) : 0, $movimentacoes)),
            'vendas' => array_sum(array_map(fn($m) => ($m['tipo'] ?? '') === 'venda' ? (int)($m['quantidade'] ?? 0) : 0, $movimentacoes)),
            'devolucoes' => array_sum(array_map(fn($m) => ($m['tipo'] ?? '') === 'devolucao' ? (int)($m['quantidade'] ?? 0) : 0, $movimentacoes)),
            'ajustes' => array_sum(array_map(fn($m) => ($m['tipo'] ?? '') === 'ajuste' ? (int)($m['quantidade'] ?? 0) : 0, $movimentacoes)),
            'faturado' => null,
        ];

        // Top produtos aproximando pelo somatório de vendas
        $vendidosPorProduto = [];
        foreach ($movimentacoes as $m) {
            if (($m['tipo'] ?? '') !== 'venda') {
                continue;
            }
            $produto = $m['descricao'] ?? ($m['produto'] ?? '');
            if (!$produto) {
                continue;
            }
            $vendidosPorProduto[$produto] = ($vendidosPorProduto[$produto] ?? 0) + (int)($m['quantidade'] ?? 0);
        }
        arsort($vendidosPorProduto);
        $topProdutos = [];
        foreach (array_slice($vendidosPorProduto, 0, 3, true) as $produto => $qtd) {
            $topProdutos[] = ['produto' => $produto, 'sku' => null, 'vendas' => $qtd, 'faturado' => null];
        }

        $this->layout('layouts/painel', 'admin/parceiros/relatorio', [
            'parceiro' => $parceiro,
            'periodo' => $periodo,
            'totais' => $totais,
            'topProdutos' => $topProdutos,
            'movimentacoes' => $movimentacoes,
        ]);
    }
}
