<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use PDO;

class NotasController extends Controller
{
    private PDO $db;

    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
        $this->db = Database::getConnection();
    }

    public function index(): void
    {
        $userId = Auth::user()?->id;
        $stmt = $this->db->prepare(
            "SELECT nf.*, p.id AS pedido_id FROM " . Database::table('notas_fiscais') . " nf
             LEFT JOIN " . Database::table('pedidos') . " p ON p.id = nf.pedido_id
             WHERE p.user_id = :user_id
             ORDER BY nf.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->layout('layouts/painel', 'cliente/notas/index', [
            'notas' => $notas,
        ]);
    }

    public function show($id): void
    {
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $stmt = $this->db->prepare(
            "SELECT nf.* FROM " . Database::table('notas_fiscais') . " nf
             LEFT JOIN " . Database::table('pedidos') . " p ON p.id = nf.pedido_id
             WHERE nf.id = :id AND p.user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $nota = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$nota) {
            http_response_code(404);
            exit('Nota fiscal não encontrada');
        }

        $this->layout('layouts/painel', 'cliente/notas/show', ['nota' => $nota]);
    }
}
