<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;

class EnderecosController extends Controller
{
    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
    }

    public function index(): void
    {
        // Mock de endereços
        $enderecos = [
            [
                'id' => 1,
                'titulo' => 'Casa',
                'linha1' => 'Rua das Flores, 123',
                'linha2' => 'Apto 45',
                'cidade' => 'São Paulo / SP',
                'cep' => '01234-567',
                'principal' => true,
            ],
            [
                'id' => 2,
                'titulo' => 'Trabalho',
                'linha1' => 'Av. Paulista, 1500',
                'linha2' => '9º andar',
                'cidade' => 'São Paulo / SP',
                'cep' => '01310-200',
                'principal' => false,
            ],
        ];

        $this->layout('layouts/painel', 'cliente/enderecos/index', [
            'enderecos' => $enderecos,
        ]);
    }
}
