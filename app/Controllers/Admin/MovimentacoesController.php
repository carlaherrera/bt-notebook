<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class MovimentacoesController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
    }

    public function index(): void
    {
        // Dados fictícios
        $resumo = [
            'entradas' => 320,
            'transferencias' => 210,
            'vendas' => 145,
            'devolucoes' => 18,
        ];

        $filtros = [
            'periodo' => 'Últimos 30 dias',
            'parceiro' => 'Todos',
            'produto' => 'Todos',
            'tipo' => 'Todos',
        ];

        $linhas = [
            ['tipo' => 'Transferência', 'descricao' => 'Envio semanal para Academia Zona Norte', 'quantidade' => 40, 'parceiro' => 'Academia Zona Norte', 'produto' => 'Whey Isolado 900g', 'data' => '2026-01-28 09:10'],
            ['tipo' => 'Venda parceiro', 'descricao' => 'Venda em Box Stronger', 'quantidade' => 6, 'parceiro' => 'Box Stronger', 'produto' => 'Creatina 300g', 'data' => '2026-01-27 18:40'],
            ['tipo' => 'Venda loja', 'descricao' => 'PDV loja física', 'quantidade' => 4, 'parceiro' => 'Loja', 'produto' => 'Pré-treino Nitro', 'data' => '2026-01-27 17:05'],
            ['tipo' => 'Devolução', 'descricao' => 'Devolução BCAA 4:1:1', 'quantidade' => 2, 'parceiro' => 'Academia Litoral', 'produto' => 'BCAA 4:1:1', 'data' => '2026-01-25 16:05'],
            ['tipo' => 'Ajuste', 'descricao' => 'Inventário loja - acerto glutamina', 'quantidade' => -1, 'parceiro' => 'Loja', 'produto' => 'Glutamina 300g', 'data' => '2026-01-24 12:10'],
            ['tipo' => 'Entrada', 'descricao' => 'Compra fornecedor - lote Whey', 'quantidade' => 120, 'parceiro' => 'Depósito', 'produto' => 'Whey Isolado 900g', 'data' => '2026-01-22 09:00'],
        ];

        $this->layout('layouts/painel', 'admin/movimentacoes/index', [
            'resumo' => $resumo,
            'filtros' => $filtros,
            'linhas' => $linhas,
        ]);
    }

    public function nova(): void
    {
        $tipos = ['Entrada', 'Transferência', 'Venda parceiro', 'Venda loja', 'Devolução', 'Ajuste'];
        $parceiros = ['Academia Zona Norte', 'Box Stronger', 'Personal Camila Duarte', 'Academia Litoral'];
        $produtos = ['Whey Isolado 900g', 'Creatina 300g', 'Pré-treino Nitro', 'BCAA 4:1:1', 'Glutamina 300g'];

        $this->layout('layouts/painel', 'admin/movimentacoes/nova', [
            'tipos' => $tipos,
            'parceiros' => $parceiros,
            'produtos' => $produtos,
        ]);
    }
}
