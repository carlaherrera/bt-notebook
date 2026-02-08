<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class ParceirosController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
    }

    private function seedParceiros(): array
    {
        return [
            [
                'id' => 1,
                'nome' => 'Academia Zona Norte',
                'tipo' => 'Academia',
                'documento' => '28.123.456/0001-77',
                'contato' => 'Marina Costa',
                'telefone' => '(11) 98888-1212',
                'email' => 'contato@zonalife.fit',
                'status' => 'Ativo',
                'cidade' => 'São Paulo - SP',
                'estoque' => [
                    'itens' => 142,
                    'baixo' => 6,
                    'devolucao' => 12,
                ],
                'vendas_mes' => 58,
                'ticket' => 'R$ 124,00',
                'atualizado' => '2026-01-28 10:12',
            ],
            [
                'id' => 2,
                'nome' => 'Box Stronger',
                'tipo' => 'Academia',
                'documento' => '18.555.901/0001-02',
                'contato' => 'Lucas Prado',
                'telefone' => '(11) 97777-3333',
                'email' => 'lucas@boxstronger.com',
                'status' => 'Ativo',
                'cidade' => 'São Paulo - SP',
                'estoque' => [
                    'itens' => 98,
                    'baixo' => 3,
                    'devolucao' => 4,
                ],
                'vendas_mes' => 34,
                'ticket' => 'R$ 132,00',
                'atualizado' => '2026-01-27 19:45',
            ],
            [
                'id' => 3,
                'nome' => 'Personal Camila Duarte',
                'tipo' => 'Personal',
                'documento' => '111.222.333-44',
                'contato' => 'Camila Duarte',
                'telefone' => '(11) 98811-0044',
                'email' => 'camila.pt@gmail.com',
                'status' => 'Ativo',
                'cidade' => 'Osasco - SP',
                'estoque' => [
                    'itens' => 52,
                    'baixo' => 2,
                    'devolucao' => 1,
                ],
                'vendas_mes' => 22,
                'ticket' => 'R$ 118,00',
                'atualizado' => '2026-01-28 08:05',
            ],
            [
                'id' => 4,
                'nome' => 'Academia Litoral',
                'tipo' => 'Academia',
                'documento' => '09.888.222/0001-90',
                'contato' => 'Patrícia Lima',
                'telefone' => '(13) 97666-5050',
                'email' => 'patricia@litoralfit.com',
                'status' => 'Inativo',
                'cidade' => 'Santos - SP',
                'estoque' => [
                    'itens' => 12,
                    'baixo' => 5,
                    'devolucao' => 0,
                ],
                'vendas_mes' => 3,
                'ticket' => 'R$ 89,00',
                'atualizado' => '2026-01-12 14:30',
            ],
        ];
    }

    public function index(): void
    {
        $parceiros = $this->seedParceiros();

        $resumo = [
            'ativos' => 3,
            'inativos' => 1,
            'itens_total' => 304,
            'parceiros' => count($parceiros),
            'estoque_baixo' => 16,
            'vendas_mes' => 117,
        ];

        $this->layout('layouts/painel', 'admin/parceiros/index', [
            'parceiros' => $parceiros,
            'resumo' => $resumo,
        ]);
    }

    public function show($id): void
    {
        $parceiros = $this->seedParceiros();
        $id = (int)$id;
        $parceiro = null;
        foreach ($parceiros as $p) {
            if ((int)($p['id'] ?? 0) === $id) {
                $parceiro = $p;
                break;
            }
        }

        if (!$parceiro) {
            http_response_code(404);
            exit('Parceiro não encontrado');
        }

        // Mocks detalhados
        $consignado = [
            ['produto' => 'Whey Isolado 900g', 'sku' => 'WHEY-ISO-900', 'categoria' => 'Proteína', 'estoque' => 24, 'min' => 8, 'vendido_mes' => 12, 'devolucao' => 2],
            ['produto' => 'Creatina 300g', 'sku' => 'CREA-300', 'categoria' => 'Performance', 'estoque' => 18, 'min' => 5, 'vendido_mes' => 9, 'devolucao' => 0],
            ['produto' => 'Pré-treino Nitro', 'sku' => 'PRE-NITRO', 'categoria' => 'Energia', 'estoque' => 9, 'min' => 6, 'vendido_mes' => 7, 'devolucao' => 1],
            ['produto' => 'BCAA 4:1:1', 'sku' => 'BCAA-411', 'categoria' => 'Aminoácidos', 'estoque' => 6, 'min' => 6, 'vendido_mes' => 3, 'devolucao' => 0],
            ['produto' => 'Glutamina 300g', 'sku' => 'GLUT-300', 'categoria' => 'Recuperação', 'estoque' => 4, 'min' => 6, 'vendido_mes' => 2, 'devolucao' => 0],
        ];

        $movimentacoes = [
            ['tipo' => 'Transferência', 'descricao' => 'Envio lote inicial', 'quantidade' => 80, 'usuario' => 'Admin', 'data' => '2026-01-20 10:22'],
            ['tipo' => 'Venda parceiro', 'descricao' => 'Pré-treino Nitro', 'quantidade' => 3, 'usuario' => 'Marina Costa', 'data' => '2026-01-27 18:40'],
            ['tipo' => 'Venda parceiro', 'descricao' => 'Whey Isolado 900g', 'quantidade' => 5, 'usuario' => 'Marina Costa', 'data' => '2026-01-27 09:12'],
            ['tipo' => 'Devolução', 'descricao' => 'Lote de BCAA 4:1:1', 'quantidade' => 2, 'usuario' => 'Marina Costa', 'data' => '2026-01-25 16:05'],
            ['tipo' => 'Ajuste', 'descricao' => 'Correção de contagem - Creatina', 'quantidade' => -1, 'usuario' => 'Admin', 'data' => '2026-01-24 12:10'],
        ];

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
        $parceiros = $this->seedParceiros();
        $id = (int)$id;
        $parceiro = null;
        foreach ($parceiros as $p) {
            if ((int)($p['id'] ?? 0) === $id) {
                $parceiro = $p;
                break;
            }
        }

        if (!$parceiro) {
            http_response_code(404);
            exit('Parceiro não encontrado');
        }

        $periodo = ['inicio' => '2026-01-01', 'fim' => '2026-01-31'];
        $totais = [
            'transferencias' => 180,
            'vendas' => 72,
            'devolucoes' => 8,
            'ajustes' => 3,
            'faturado' => 'R$ 14.820,00',
        ];
        $topProdutos = [
            ['produto' => 'Whey Isolado 900g', 'sku' => 'WHEY-ISO-900', 'vendas' => 32, 'faturado' => 'R$ 6.076,80'],
            ['produto' => 'Creatina 300g', 'sku' => 'CREA-300', 'vendas' => 18, 'faturado' => 'R$ 1.618,20'],
            ['produto' => 'Pré-treino Nitro', 'sku' => 'PRE-NITRO', 'vendas' => 11, 'faturado' => 'R$ 1.428,90'],
        ];
        $movimentacoes = [
            ['tipo' => 'Venda', 'descricao' => 'Whey Isolado 900g', 'quantidade' => 3, 'valor' => 'R$ 569,70', 'data' => '2026-01-28 18:40'],
            ['tipo' => 'Venda', 'descricao' => 'Creatina 300g', 'quantidade' => 2, 'valor' => 'R$ 179,80', 'data' => '2026-01-28 11:05'],
            ['tipo' => 'Transferência', 'descricao' => 'Reposição semanal', 'quantidade' => 40, 'valor' => '-', 'data' => '2026-01-27 09:10'],
            ['tipo' => 'Devolução', 'descricao' => 'BCAA 4:1:1', 'quantidade' => 2, 'valor' => '-', 'data' => '2026-01-25 16:05'],
            ['tipo' => 'Ajuste', 'descricao' => 'Glutamina 300g correção', 'quantidade' => -1, 'valor' => '-', 'data' => '2026-01-24 12:10'],
        ];

        $this->layout('layouts/painel', 'admin/parceiros/relatorio', [
            'parceiro' => $parceiro,
            'periodo' => $periodo,
            'totais' => $totais,
            'topProdutos' => $topProdutos,
            'movimentacoes' => $movimentacoes,
        ]);
    }
}
