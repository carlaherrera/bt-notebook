<?php

namespace App\Controllers\Admin;

use App\Core\Controller;

class AuditoriaController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
    }

    public function index(): void
    {
        $resumo = [
            'contagens_pendentes' => 3,
            'divergencias' => 2,
            'ajustes_aplicados' => 5,
            'auditorias_mes' => 8,
        ];

        $checklist = [
            ['produto' => 'Whey Isolado 900g', 'local' => 'Loja física', 'status' => 'Pendente', 'qtde_sistema' => 120, 'qtde_fisica' => null],
            ['produto' => 'Creatina 300g', 'local' => 'Box Stronger', 'status' => 'Divergência', 'qtde_sistema' => 55, 'qtde_fisica' => 52],
            ['produto' => 'BCAA 4:1:1', 'local' => 'Academia Litoral', 'status' => 'Divergência', 'qtde_sistema' => 9, 'qtde_fisica' => 7],
        ];

        $historico = [
            ['acao' => 'Ajuste aplicado', 'descricao' => 'Corrigido estoque Creatina 300g (Box Stronger)', 'usuario' => 'Admin', 'data' => '2026-01-28 14:10'],
            ['acao' => 'Contagem concluída', 'descricao' => 'Whey Isolado - Loja física', 'usuario' => 'Marina Costa', 'data' => '2026-01-28 10:05'],
            ['acao' => 'Divergência registrada', 'descricao' => 'BCAA 4:1:1 - Academia Litoral', 'usuario' => 'Patrícia Lima', 'data' => '2026-01-27 18:32'],
        ];

        $this->layout('layouts/painel', 'admin/auditoria/index', [
            'resumo' => $resumo,
            'checklist' => $checklist,
            'historico' => $historico,
        ]);
    }
}
