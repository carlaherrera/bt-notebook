<?php
// /app/Controllers/Publico/InicioController.php

declare(strict_types=1);

namespace App\Controllers\Publico;

use App\Core\Controller;

class InicioController extends Controller
{
    public function index(): void
    {
        $this->view('publico/inicio');
    }
}
