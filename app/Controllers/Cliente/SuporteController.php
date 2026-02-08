<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;

class SuporteController extends Controller
{
    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
    }

    public function index(): void
    {
        // Mock de tickets
        $tickets = [
            [
                'id' => '#T-2301',
                'assunto' => 'Atraso na entrega',
                'status' => 'Aberto',
                'atualizado' => 'Há 2h',
                'prioridade' => 'Alta',
            ],
            [
                'id' => '#T-2294',
                'assunto' => 'Erro na fatura',
                'status' => 'Em atendimento',
                'atualizado' => 'Hoje, 10h',
                'prioridade' => 'Média',
            ],
            [
                'id' => '#T-2288',
                'assunto' => 'Troca de produto',
                'status' => 'Resolvido',
                'atualizado' => 'Ontem',
                'prioridade' => 'Baixa',
            ],
        ];

        $this->layout('layouts/painel', 'cliente/suporte/index', [
            'tickets' => $tickets,
        ]);
    }

    public function create(): void
    {
        $categorias = ['Entrega', 'Pagamento', 'Troca/Devolução', 'Outros'];
        $this->layout('layouts/painel', 'cliente/suporte/novo', [
            'categorias' => $categorias,
        ]);
    }
}
