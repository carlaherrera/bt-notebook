<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class ProdutosController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
    }

    private function seedProdutos(): array
    {
        return [
            [
                'id' => 1,
                'nome' => 'Whey Isolado 900g',
                'sku' => 'WHEY-ISO-900',
                'categoria' => 'Proteína',
                'preco' => 'R$ 189,90',
                'estoque_loja' => 120,
                'estoque_consignado' => 68,
                'minimo' => 40,
                'status' => 'Ativo',
                'foto' => null,
            ],
            [
                'id' => 2,
                'nome' => 'Creatina 300g',
                'sku' => 'CREA-300',
                'categoria' => 'Performance',
                'preco' => 'R$ 89,90',
                'estoque_loja' => 80,
                'estoque_consignado' => 55,
                'minimo' => 30,
                'status' => 'Ativo',
                'foto' => null,
            ],
            [
                'id' => 3,
                'nome' => 'Pré-treino Nitro',
                'sku' => 'PRE-NITRO',
                'categoria' => 'Energia',
                'preco' => 'R$ 129,90',
                'estoque_loja' => 22,
                'estoque_consignado' => 18,
                'minimo' => 20,
                'status' => 'Ativo',
                'foto' => null,
            ],
            [
                'id' => 4,
                'nome' => 'BCAA 4:1:1',
                'sku' => 'BCAA-411',
                'categoria' => 'Aminoácidos',
                'preco' => 'R$ 69,90',
                'estoque_loja' => 14,
                'estoque_consignado' => 9,
                'minimo' => 18,
                'status' => 'Alerta',
                'foto' => null,
            ],
            [
                'id' => 5,
                'nome' => 'Glutamina 300g',
                'sku' => 'GLUT-300',
                'categoria' => 'Recuperação',
                'preco' => 'R$ 79,90',
                'estoque_loja' => 9,
                'estoque_consignado' => 6,
                'minimo' => 15,
                'status' => 'Crítico',
                'foto' => null,
            ],
            [
                'id' => 6,
                'nome' => 'Barra Proteica 45g',
                'sku' => 'BARRA-45',
                'categoria' => 'Snacks',
                'preco' => 'R$ 12,90',
                'estoque_loja' => 260,
                'estoque_consignado' => 130,
                'minimo' => 80,
                'status' => 'Ativo',
                'foto' => null,
            ],
        ];
    }

    public function index(): void
    {
        $produtos = $this->seedProdutos();

        $resumo = [
            'ativos' => 5,
            'criticos' => 1,
            'itens_loja' => array_sum(array_column($produtos, 'estoque_loja')),
            'itens_consignado' => array_sum(array_column($produtos, 'estoque_consignado')),
        ];

        $this->layout('layouts/painel', 'admin/produtos/index', [
            'produtos' => $produtos,
            'resumo' => $resumo,
        ]);
    }

    public function show($id): void
    {
        $produtos = $this->seedProdutos();
        $id = (int)$id;
        $produto = null;
        foreach ($produtos as $p) {
            if ((int)($p['id'] ?? 0) === $id) {
                $produto = $p;
                break;
            }
        }

        if (!$produto) {
            http_response_code(404);
            exit('Produto não encontrado');
        }

        $parceirosTop = [
            ['nome' => 'Academia Zona Norte', 'cidade' => 'São Paulo - SP', 'vendido' => 24],
            ['nome' => 'Box Stronger', 'cidade' => 'São Paulo - SP', 'vendido' => 16],
            ['nome' => 'Personal Camila Duarte', 'cidade' => 'Osasco - SP', 'vendido' => 9],
        ];

        $movimentacoes = [
            ['tipo' => 'Transferência', 'descricao' => 'Lote semanal', 'quantidade' => 30, 'local' => 'Parceiros', 'data' => '2026-01-28 09:00'],
            ['tipo' => 'Venda', 'descricao' => 'Pedido loja física', 'quantidade' => 4, 'local' => 'Loja', 'data' => '2026-01-27 18:10'],
            ['tipo' => 'Venda parceiro', 'descricao' => 'Academia Zona Norte', 'quantidade' => 6, 'local' => 'Parceiros', 'data' => '2026-01-27 10:32'],
            ['tipo' => 'Devolução', 'descricao' => 'Box Stronger - ajuste', 'quantidade' => 2, 'local' => 'Parceiros', 'data' => '2026-01-25 15:05'],
            ['tipo' => 'Ajuste', 'descricao' => 'Inventário loja', 'quantidade' => -1, 'local' => 'Loja', 'data' => '2026-01-24 12:10'],
        ];

        $analytics = [
            'vendido_mes' => 62,
            'ticket_medio' => 'R$ 142,00',
            'estoque_total' => ($produto['estoque_loja'] ?? 0) + ($produto['estoque_consignado'] ?? 0),
            'status' => $produto['status'] ?? 'Ativo',
        ];

        $this->layout('layouts/painel', 'admin/produtos/show', [
            'produto' => $produto,
            'parceirosTop' => $parceirosTop,
            'movimentacoes' => $movimentacoes,
            'analytics' => $analytics,
        ]);
    }
}
