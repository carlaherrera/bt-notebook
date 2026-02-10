<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use PDO;

class PainelController extends Controller
{
    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
    }

    public function index(): void
    {
        $db = Database::getConnection();
        $userId = Auth::user()?->id;

        // Métricas de pedidos
        $stmt = $db->prepare("SELECT 
            COUNT(*) as total,
            SUM(status = 'entregue') as entregues,
            SUM(status = 'em_rota') as em_rota,
            SUM(status = 'pago') as pagos,
            SUM(status IN ('criado','pago','em_rota')) as em_andamento
            FROM " . Database::table('pedidos') . " WHERE user_id = :uid");
        $stmt->execute(['uid' => $userId]);
        $pedidosMetrics = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        // Tickets abertos ou em atendimento
        $stmt = $db->prepare("SELECT 
            SUM(status IN ('aberto','em_atendimento')) as tickets_abertos,
            COUNT(*) as tickets_total
            FROM " . Database::table('tickets') . " WHERE user_id = :uid");
        $stmt->execute(['uid' => $userId]);
        $ticketMetrics = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        // Notas fiscais
        $stmt = $db->prepare("SELECT COUNT(*) as notas_total FROM " . Database::table('notas_fiscais') . " nf 
            INNER JOIN " . Database::table('pedidos') . " p ON p.id = nf.pedido_id
            WHERE p.user_id = :uid");
        $stmt->execute(['uid' => $userId]);
        $notasMetrics = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        // Faturas pendentes
        $stmt = $db->prepare("SELECT COUNT(*) as faturas_pendentes FROM " . Database::table('faturas') . " f
            INNER JOIN " . Database::table('pedidos') . " p ON p.id = f.pedido_id
            WHERE p.user_id = :uid AND f.status = 'pendente'");
        $stmt->execute(['uid' => $userId]);
        $faturasMetrics = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        // Últimos pedidos
        $stmt = $db->prepare("SELECT id, status, total, created_at FROM " . Database::table('pedidos') . "
            WHERE user_id = :uid ORDER BY created_at DESC LIMIT 5");
        $stmt->execute(['uid' => $userId]);
        $pedidosRecentes = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $this->layout('layouts/painel', 'cliente/painel/index', [
            'pedidosMetrics' => $pedidosMetrics,
            'ticketMetrics' => $ticketMetrics,
            'notasMetrics' => $notasMetrics,
            'faturasMetrics' => $faturasMetrics,
            'pedidosRecentes' => $pedidosRecentes,
        ]);
    }
}
