<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;

class PagamentosController extends Controller
{
    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
    }

    public function index(): void
    {
        $metodos = [
            ['tipo' => 'Cartão', 'apelido' => 'Visa final 1234', 'validade' => '08/28', 'principal' => true],
            ['tipo' => 'PIX', 'apelido' => 'PIX João', 'validade' => null, 'principal' => false],
        ];

        $faturas = [
            ['id' => '#F-102', 'data' => '05/02/2026', 'valor' => 'R$ 249,90', 'status' => 'Pago'],
            ['id' => '#F-101', 'data' => '29/01/2026', 'valor' => 'R$ 189,00', 'status' => 'Pago'],
        ];

        $this->layout('layouts/painel', 'cliente/pagamentos/index', [
            'metodos' => $metodos,
            'faturas' => $faturas,
        ]);
    }
}
