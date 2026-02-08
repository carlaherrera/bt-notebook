<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class RelatoriosController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
    }

    public function index(): void
    {
        $relatorios = [
            ['nome' => 'Estoque loja física', 'descricao' => 'Posição atual de estoque da loja', 'formato' => 'PDF, Excel', 'ultima' => '2026-01-28 10:15'],
            ['nome' => 'Estoque por parceiro', 'descricao' => 'Snapshot consignado por parceiro', 'formato' => 'PDF, Excel', 'ultima' => '2026-01-28 09:40'],
            ['nome' => 'Movimentações por período', 'descricao' => 'Entradas, transferências, vendas e devoluções', 'formato' => 'PDF, Excel, CSV', 'ultima' => '2026-01-27 18:00'],
            ['nome' => 'Produtos abaixo do mínimo', 'descricao' => 'Itens críticos ou em alerta', 'formato' => 'PDF, Excel', 'ultima' => '2026-01-28 08:10'],
            ['nome' => 'Desempenho por parceiro', 'descricao' => 'Vendas e devoluções por parceiro', 'formato' => 'PDF, Excel', 'ultima' => '2026-01-27 20:05'],
        ];

        $filtros = [
            'periodo' => 'Últimos 30 dias',
            'parceiro' => 'Todos',
            'produto' => 'Todos',
            'saida' => 'PDF',
        ];

        $this->layout('layouts/painel', 'admin/relatorios/index', [
            'relatorios' => $relatorios,
            'filtros' => $filtros,
        ]);
    }
}
