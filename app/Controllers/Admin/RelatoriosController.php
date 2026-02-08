<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class RelatoriosController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->requireActive();
        $this->db = Database::getConnection();
    }

    public function index(): void
    {
        $logs = $this->db->query(
            "SELECT rl.id, rl.nome, rl.formato, rl.filtros, rl.gerado_em, u.nome AS usuario
             FROM " . Database::table('relatorios_log') . " rl
             LEFT JOIN " . Database::table('usuarios') . " u ON u.id = rl.usuario_id
             ORDER BY rl.gerado_em DESC
             LIMIT 50"
        )->fetchAll(PDO::FETCH_ASSOC);

        $filtros = [
            'periodo' => 'Últimos 30 dias',
            'saida' => 'Todos',
        ];

        $this->layout('layouts/painel', 'admin/relatorios/index', [
            'logs' => $logs,
            'filtros' => $filtros,
        ]);
    }
}
