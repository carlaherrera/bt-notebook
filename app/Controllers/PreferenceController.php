<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Security;

class PreferenceController extends Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function salvarTema(): void
    {
        $this->validateCsrf();

        $userId = Auth::user()?->id;
        if (!$userId) {
            http_response_code(401);
            return;
        }

        $theme = Security::sanitizeString($_POST['theme_preference'] ?? 'system');
        $theme = in_array($theme, ['light', 'dark', 'system'], true) ? $theme : 'system';

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

        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'theme' => $theme]);
    }
}
