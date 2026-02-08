<?php

namespace App\Controllers\Cliente;

use App\Core\Controller;

class PainelController extends Controller
{
    public function __construct()
    {
        $this->requireRole('cliente');
        $this->requireActive();
    }

    public function index(): void
    {
        $this->layout('layouts/painel', 'cliente/painel/index');
    }
}
