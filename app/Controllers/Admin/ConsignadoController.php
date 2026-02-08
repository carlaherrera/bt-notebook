<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class ConsignadoController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
    }

    public function index(): void
    {
        $parceiros = $this->seedParceiros();

        $resumo = [
            'total_itens' => array_sum(array_column($parceiros, 'itens')),
            'itens_alerta' => array_sum(array_column($parceiros, 'baixo')),
            'vendas_mes' => array_sum(array_column($parceiros, 'vendas_mes')),
            'transferencias_mes' => array_sum(array_column($parceiros, 'transferencias_mes')),
        ];

        $this->layout('layouts/painel', 'admin/consignado/index', [
            'parceiros' => $parceiros,
            'resumo' => $resumo,
        ]);
    }

    private function seedParceiros(): array
    {
        return [
            [
                'id' => 1,
                'nome' => 'Academia Zona Norte',
                'cidade' => 'São Paulo - SP',
                'itens' => 142,
                'baixo' => 6,
                'devolucao' => 12,
                'vendas_mes' => 58,
                'transferencias_mes' => 80,
                'prazo_devolucao' => '2026-02-05',
                'nf' => 'NF-2026-00123',
                'reposicao_agendada' => '2026-02-02 10:00',
            ],
            [
                'id' => 2,
                'nome' => 'Box Stronger',
                'cidade' => 'São Paulo - SP',
                'itens' => 98,
                'baixo' => 3,
                'devolucao' => 4,
                'vendas_mes' => 34,
                'transferencias_mes' => 60,
                'prazo_devolucao' => '2026-02-03',
                'nf' => 'NF-2026-00110',
                'reposicao_agendada' => '2026-02-01 15:00',
            ],
            [
                'id' => 3,
                'nome' => 'Personal Camila Duarte',
                'cidade' => 'Osasco - SP',
                'itens' => 52,
                'baixo' => 2,
                'devolucao' => 1,
                'vendas_mes' => 22,
                'transferencias_mes' => 40,
                'prazo_devolucao' => '2026-02-07',
                'nf' => 'NF-2026-00105',
                'reposicao_agendada' => '2026-02-04 09:30',
            ],
            [
                'id' => 4,
                'nome' => 'Academia Litoral',
                'cidade' => 'Santos - SP',
                'itens' => 12,
                'baixo' => 5,
                'devolucao' => 0,
                'vendas_mes' => 3,
                'transferencias_mes' => 10,
                'prazo_devolucao' => '2026-02-10',
                'nf' => 'NF-2026-00088',
                'reposicao_agendada' => null,
            ],
        ];
    }

    public function parceiro($id): void
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

        $produtos = [
            ['produto' => 'Whey Isolado 900g', 'sku' => 'WHEY-ISO-900', 'estoque' => 24, 'min' => 8, 'vendido_mes' => 12, 'devolucao' => 2, 'lote' => 'L2301', 'nf' => 'NF-2026-00123', 'prazo_dev' => '2026-02-05'],
            ['produto' => 'Creatina 300g', 'sku' => 'CREA-300', 'estoque' => 18, 'min' => 5, 'vendido_mes' => 9, 'devolucao' => 0, 'lote' => 'L2302', 'nf' => 'NF-2026-00123', 'prazo_dev' => '2026-02-05'],
            ['produto' => 'Pré-treino Nitro', 'sku' => 'PRE-NITRO', 'estoque' => 9, 'min' => 6, 'vendido_mes' => 7, 'devolucao' => 1, 'lote' => 'L2209', 'nf' => 'NF-2026-00099', 'prazo_dev' => '2026-02-03'],
            ['produto' => 'BCAA 4:1:1', 'sku' => 'BCAA-411', 'estoque' => 6, 'min' => 6, 'vendido_mes' => 3, 'devolucao' => 0, 'lote' => 'L2210', 'nf' => 'NF-2026-00088', 'prazo_dev' => '2026-02-10'],
            ['produto' => 'Glutamina 300g', 'sku' => 'GLUT-300', 'estoque' => 4, 'min' => 6, 'vendido_mes' => 2, 'devolucao' => 0, 'lote' => 'L2211', 'nf' => 'NF-2026-00105', 'prazo_dev' => '2026-02-07'],
        ];

        $movimentacoes = [
            ['tipo' => 'Transferência', 'descricao' => 'Envio lote semanal', 'quantidade' => 40, 'data' => '2026-01-27 09:10'],
            ['tipo' => 'Venda parceiro', 'descricao' => 'Whey Isolado 900g', 'quantidade' => 5, 'data' => '2026-01-28 10:15'],
            ['tipo' => 'Venda parceiro', 'descricao' => 'Creatina 300g', 'quantidade' => 3, 'data' => '2026-01-28 18:40'],
            ['tipo' => 'Devolução', 'descricao' => 'BCAA 4:1:1', 'quantidade' => 2, 'data' => '2026-01-25 16:05'],
            ['tipo' => 'Ajuste', 'descricao' => 'Correção inventário', 'quantidade' => -1, 'data' => '2026-01-24 12:10'],
        ];

        $this->layout('layouts/painel', 'admin/consignado/show', [
            'parceiro' => $parceiro,
            'produtos' => $produtos,
            'movimentacoes' => $movimentacoes,
        ]);
    }
}
