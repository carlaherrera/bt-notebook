<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;

class PedidosController extends Controller
{
    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
    }

    public function index(): void
    {
        // Mock de pedidos (substituir por DB quando disponível)
        $pedidos = [
            [
                'id' => '#1023',
                'data' => '2026-02-08',
                'status' => 'Em separação',
                'total' => 'R$ 249,90',
                'itens' => [
                    ['nome' => 'Whey Protein 900g', 'qtd' => 1],
                    ['nome' => 'Creatina 300g', 'qtd' => 1],
                ],
            ],
            [
                'id' => '#1019',
                'data' => '2026-02-05',
                'status' => 'Entregue',
                'total' => 'R$ 189,00',
                'itens' => [
                    ['nome' => 'BCAA 120 caps', 'qtd' => 2],
                ],
            ],
            [
                'id' => '#1014',
                'data' => '2026-01-29',
                'status' => 'Em rota',
                'total' => 'R$ 320,00',
                'itens' => [
                    ['nome' => 'Pré-treino 300g', 'qtd' => 1],
                    ['nome' => 'Coqueteleira', 'qtd' => 1],
                ],
            ],
        ];

        $this->layout('layouts/painel', 'cliente/pedidos/index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function create(): void
    {
        $produtos = [
            ['id' => 1, 'nome' => 'Whey Protein 900g', 'preco' => 'R$ 189,90'],
            ['id' => 2, 'nome' => 'Creatina 300g', 'preco' => 'R$ 89,90'],
            ['id' => 3, 'nome' => 'Pré-treino 300g', 'preco' => 'R$ 129,90'],
        ];
        $enderecos = [
            ['id' => 1, 'label' => 'Casa · Rua das Flores, 123'],
            ['id' => 2, 'label' => 'Trabalho · Av. Paulista, 1500'],
        ];
        $pagamentos = [
            ['id' => 1, 'label' => 'Visa final 1234'],
            ['id' => 2, 'label' => 'PIX João'],
        ];

        $this->layout('layouts/painel', 'cliente/pedidos/novo', [
            'produtos' => $produtos,
            'enderecos' => $enderecos,
            'pagamentos' => $pagamentos,
        ]);
    }
}
