<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;

class NotasController extends Controller
{
    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
    }

    public function index(): void
    {
        $notas = [
            ['id' => '#NF-2201', 'data' => '05/02/2026', 'pedido' => '#1023', 'valor' => 'R$ 249,90', 'link' => '#'],
            ['id' => '#NF-2195', 'data' => '29/01/2026', 'pedido' => '#1019', 'valor' => 'R$ 189,00', 'link' => '#'],
        ];

        $this->layout('layouts/painel', 'cliente/notas/index', [
            'notas' => $notas,
        ]);
    }
}
