<?php

namespace App\Controllers\Colaborador;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Database;

class PreferenciasController extends Controller
{
    public function __construct()
    {
        $this->requireRole('colaborador');
    }

    public function index(): void
    {
        $userId = Auth::user()?->id;
        $theme = $this->getPreference((int) $userId);
        $this->layout('layouts/painel', 'colaborador/preferencias/index', ['theme' => $theme]);
    }

    public function salvar(): void
    {
        $this->validateCsrf();
        $userId = (int) (Auth::user()?->id ?? 0);
        if (!$userId) {
            $this->redirect('/colaborador/preferencias');
        }
        $theme = Security::sanitizeString($_POST['theme_preference'] ?? 'system');
        $this->savePreference($userId, $theme);
        $this->redirect('/colaborador/preferencias');
    }

    private function getPreference(int $userId): string
    {
        if ($userId <= 0) return 'system';
        $pdo = Database::getConnection();
        $prefsTable = Database::table('user_preferences');
        $stmt = $pdo->prepare('SELECT theme_preference FROM ' . $prefsTable . ' WHERE user_id = :uid LIMIT 1');
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchColumn() ?: 'system';
    }

    private function savePreference(int $userId, string $theme): void
    {
        $pdo = Database::getConnection();
        $prefsTable = Database::table('user_preferences');
        $stmt = $pdo->prepare('SELECT id FROM ' . $prefsTable . ' WHERE user_id = :uid LIMIT 1');
        $stmt->execute(['uid' => $userId]);
        $id = $stmt->fetchColumn();
        if ($id) {
            $update = $pdo->prepare('UPDATE ' . $prefsTable . ' SET theme_preference = :theme WHERE id = :id');
            $update->execute(['theme' => $theme, 'id' => $id]);
        } else {
            $insert = $pdo->prepare('INSERT INTO ' . $prefsTable . ' (user_id, theme_preference) VALUES (:uid, :theme)');
            $insert->execute(['uid' => $userId, 'theme' => $theme]);
        }
    }
}
