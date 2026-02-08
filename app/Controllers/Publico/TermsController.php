<?php

declare(strict_types=1);

namespace App\Controllers\Publico;

use App\Core\Controller;
use App\Core\Security;

class TermsController extends Controller
{
    public function index(): void
    {
        $this->view('publico/termos', []);
    }

    public function aceitar(): void
    {
        $this->validateCsrf();

        if (empty($_POST['aceito'])) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Você precisa aceitar os termos para continuar.'];
            $this->redirect('/termos');
        }

        $_SESSION['accepted_terms'] = true;
        $redirect = $_SESSION['after_terms_redirect'] ?? '/';
        if ($redirect === '/termos') {
            $redirect = '/';
        }
        unset($_SESSION['after_terms_redirect']);

        $this->redirect($redirect);
    }
}
