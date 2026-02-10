<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Security;
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
            "SELECT nf.id, nf.numero, nf.serie, nf.valor, nf.data_emissao, nf.link_download, nf.pedido_id
             FROM " . Database::table('notas_fiscais') . " nf
             LEFT JOIN " . Database::table('pedidos') . " p ON p.id = nf.pedido_id
             WHERE p.user_id = :user_id
             ORDER BY nf.data_emissao DESC, nf.id DESC"
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

    public function edit($id): void
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

        $this->layout('layouts/painel', 'cliente/notas/editar', [
            'nota' => $nota,
        ]);
    }

    public function update($id): void
    {
        $this->validateCsrf();
        $id = (int)$id;
        $userId = Auth::user()?->id;

        $numero = Security::sanitizeString($_POST['numero'] ?? '');
        $serie = Security::sanitizeString($_POST['serie'] ?? '');
        $data_emissao = Security::sanitizeString($_POST['data_emissao'] ?? '');
        $valor = (float)($_POST['valor'] ?? 0);
        $link = Security::sanitizeString($_POST['link_download'] ?? '');

        if (!$numero || !$data_emissao) {
            http_response_code(400);
            exit('Número e data são obrigatórios');
        }

        $stmt = $this->db->prepare(
            "SELECT nf.id FROM " . Database::table('notas_fiscais') . " nf
             LEFT JOIN " . Database::table('pedidos') . " p ON p.id = nf.pedido_id
             WHERE nf.id = :id AND p.user_id = :user_id"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            exit('Nota fiscal não encontrada');
        }

        $upd = $this->db->prepare(
            "UPDATE " . Database::table('notas_fiscais') . "
             SET numero = :numero, serie = :serie, valor = :valor, data_emissao = :data_emissao, link_download = :link
             WHERE id = :id"
        );
        $upd->execute([
            'numero' => $numero,
            'serie' => $serie,
            'valor' => $valor,
            'data_emissao' => $data_emissao,
            'link' => $link,
            'id' => $id,
        ]);

        $this->redirect('/cliente/notas/' . $id . '?ok=1');
    }
}
