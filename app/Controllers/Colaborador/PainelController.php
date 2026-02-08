<?php

namespace App\Controllers\Colaborador;

use App\Core\Controller;

class PainelController extends Controller
{
    public function __construct()
    {
        $this->requireRole('colaborador');
        $this->requireActive();
    }

    public function index(): void
    {
        $this->layout('layouts/painel', 'colaborador/painel/index');
    }
}
