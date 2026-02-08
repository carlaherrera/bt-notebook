<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Security;
use PDO;

class ConfiguracoesController extends Controller
{
    public function __construct()
    {
        $this->requireRole('admin');
    }

    public function index(): void
    {

        $pdo = Database::getConnection();
        $settingsTable = Database::table('settings');
        $stmt = $pdo->query('SELECT * FROM ' . $settingsTable . ' LIMIT 1');
        $settings = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $pref = $this->getUserPreference(Auth::user()?->id);

        $this->layout('layouts/painel', 'admin/configuracoes/index', [
            'settings' => $settings,
            'themePref' => $pref,
        ]);
    }

    public function salvar(): void
    {
        $this->validateCsrf();

        $pdo = Database::getConnection();

        // settings atuais (para manter caminhos anteriores)
        $settingsTable = Database::table('settings');
        $prefsTable = Database::table('user_preferences');
        $current = $pdo->query('SELECT * FROM ' . $settingsTable . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC) ?: [];
        $oldLogoLight = $current['logo_light_path'] ?? null;
        $oldLogoDark = $current['logo_dark_path'] ?? null;
        $oldFavicon = $current['favicon_path'] ?? null;

        // Processar uploads/remoções de imagem
        try {
            $this->debug('FILES recebidos: ' . json_encode(array_keys($_FILES)));
            $this->debug('POST recebido: ' . json_encode(array_keys($_POST)));

            $logoLight = $this->processMediaField('logo_light_path', $oldLogoLight);
            $logoDark  = $this->processMediaField('logo_dark_path', $oldLogoDark);
            $favicon   = $this->processMediaField('favicon_path', $oldFavicon);
        } catch (\Exception $e) {
            $this->debug('Erro no upload: ' . $e->getMessage() . ' - ' . $e->getFile() . ':' . $e->getLine());
        }

        $org = [
            'org_name' => Security::sanitizeString($_POST['org_name'] ?? ''),
            'slogan' => Security::sanitizeString($_POST['slogan'] ?? ''),
            'cep' => Security::sanitizeString($_POST['cep'] ?? ''),
            'rua' => Security::sanitizeString($_POST['rua'] ?? ''),
            'numero' => Security::sanitizeString($_POST['numero'] ?? ''),
            'cidade' => Security::sanitizeString($_POST['cidade'] ?? ''),
            'estado' => Security::sanitizeString($_POST['estado'] ?? ''),
            'telefone' => Security::sanitizeString($_POST['telefone'] ?? ''),
            'whatsapp' => Security::sanitizeString($_POST['whatsapp'] ?? ''),
            'email' => Security::sanitizeString($_POST['email'] ?? ''),
            'cnpj' => Security::sanitizeString($_POST['cnpj'] ?? ''),
            'logo_light_path' => $this->resolveMediaField('logo_light_path', $logoLight, $oldLogoLight),
            'logo_dark_path' => $this->resolveMediaField('logo_dark_path', $logoDark, $oldLogoDark),
            'favicon_path' => $this->resolveMediaField('favicon_path', $favicon, $oldFavicon),
            'primary_color' => Security::sanitizeString($_POST['primary_color'] ?? ''),
            'secondary_color' => Security::sanitizeString($_POST['secondary_color'] ?? ''),
        ];

        $stmt = $pdo->query('SELECT id FROM ' . $settingsTable . ' LIMIT 1');
        $exists = $stmt->fetchColumn();

        $this->debug('Dados a salvar: ' . json_encode($org));

        $columns = array_keys($org);
        $placeholders = array_map(fn($c) => ':' . $c, $columns);

        if ($exists) {
            $this->debug('Atualizando settings (ID: ' . $exists . ')');
            $sets = implode(', ', array_map(fn($c) => "{$c}=:{$c}", $columns));
            $sql = 'UPDATE ' . $settingsTable . ' SET ' . $sets . ' WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $org['id'] = $exists;
            $result = $stmt->execute($org);
            $this->debug('UPDATE executado. Resultado: ' . ($result ? 'sucesso' : 'falha'));
        } else {
            $this->debug('Inserindo novo settings');
            $colsList = implode(', ', $columns);
            $phList = implode(', ', $placeholders);
            $sql = "INSERT INTO {$settingsTable} ({$colsList}) VALUES ({$phList})";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($org);
            $this->debug('INSERT executado. Resultado: ' . ($result ? 'sucesso' : 'falha'));
        }

        // preferência do usuário
        $userId = Auth::user()?->id;
        if ($userId) {
            $theme = Security::sanitizeString($_POST['theme_preference'] ?? 'system');
            $this->saveUserPreference($userId, $theme);
        }

        $this->redirect('/admin/configuracoes');
    }

    public function cep(string $cep): void
    {
        header('Content-Type: application/json');
        $clean = preg_replace('/\D/', '', $cep ?? '');
        if (strlen($clean) !== 8) {
            http_response_code(400);
            echo json_encode(['error' => 'CEP inválido']);
            return;
        }

        $endpoints = [
            "https://viacep.com.br/ws/{$clean}/json/",
            "https://opencep.com/v1/{$clean}",
        ];

        foreach ($endpoints as $url) {
            $data = $this->fetchCepFrom($url);
            if ($data) {
                echo json_encode([
                    'logradouro' => $data['logradouro'] ?? $data['street'] ?? '',
                    'localidade' => $data['localidade'] ?? $data['city'] ?? '',
                    'uf' => $data['uf'] ?? $data['state'] ?? '',
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'CEP não encontrado']);
    }

    private function fetchCepFrom(string $url): ?array
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                ],
                'https' => [
                    'timeout' => 5,
                ],
            ]);
            $response = @file_get_contents($url, false, $context);
            if (!$response) {
                return null;
            }
            $data = json_decode($response, true);
            if (!is_array($data) || ($data['erro'] ?? false)) {
                return null;
            }
            return $data;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function getUserPreference(?int $userId): string
    {
        if (!$userId) {
            return 'system';
        }
        $pdo = Database::getConnection();
        $prefsTable = Database::table('user_preferences');
        $stmt = $pdo->prepare('SELECT theme_preference FROM ' . $prefsTable . ' WHERE user_id = :uid LIMIT 1');
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchColumn() ?: 'system';
    }

    private function saveUserPreference(int $userId, string $theme): void
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

    private function resolveMediaField(string $field, ?string $newValue, ?string $oldValue): string
    {
        $removeFlag = isset($_POST["{$field}_remove"]) && $_POST["{$field}_remove"] === '1';
        if ($removeFlag) {
            if ($oldValue) {
                $absolute = str_starts_with($oldValue, '/')
                    ? BASE_PATH . '/public' . $oldValue
                    : $oldValue;
                if (is_file($absolute)) {
                    @unlink($absolute);
                }
            }
            return '';
        }

        if ($newValue) {
            return $newValue;
        }

        return $oldValue ? Security::sanitizeString($oldValue) : '';
    }

    /**
     * Lida com upload/remoção de um campo de mídia (logo/fav).
     * Retorna string com novo caminho, '' se removido, ou null para manter o existente.
     */
    private function processMediaField(string $field, ?string $oldValue): ?string
    {
        $removeFlag = isset($_POST["{$field}_remove"]) && $_POST["{$field}_remove"] === '1';

        // Remover arquivo atual
        if ($removeFlag && $oldValue) {
            $absolute = $oldValue;
            if (!str_starts_with($absolute, BASE_PATH)) {
                $absolute = BASE_PATH . '/public/' . ltrim($absolute, '/');
            }
            if (is_file($absolute)) {
                @unlink($absolute);
            }
            return '';
        }

        // Upload novo
        $hasUpload = isset($_FILES[$field]) && ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK;
        if ($hasUpload) {
            $uploaded = $this->handleImageUpload($field, '/public/uploads/configuracoes', $oldValue);
            if ($uploaded) {
                return $uploaded;
            }
        }

        // Sem mudanças
        return null;
    }

}
