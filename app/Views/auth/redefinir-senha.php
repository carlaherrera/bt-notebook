<?php
use App\Core\Security;
use App\Core\Database;

$title = 'Redefinir Senha - FerramentasAi';
$description = 'Defina uma nova senha para sua conta FerramentasAi';

$faviconFallback = '/uploads/fallback-images/favicon-fallback.svg';
$faviconPath = $faviconFallback;
$orgName = 'FerramentasAi';

try {
    $pdo = Database::getConnection();
    $settingsTable = Database::table('settings');
    $settingsRow = $pdo->query('SELECT org_name, favicon_path FROM ' . $settingsTable . ' LIMIT 1')->fetch(\PDO::FETCH_ASSOC);
    if (!empty($settingsRow['favicon_path'])) {
        $faviconPath = $settingsRow['favicon_path'];
    }
    if (!empty($settingsRow['org_name'])) {
        $orgName = $settingsRow['org_name'];
    }
} catch (\Throwable $e) {
    // mantém fallback
}
?>

<?php
$content = '';
ob_start();
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col items-center justify-center px-4 py-8 sm:py-12">
    <div class="w-full max-w-md">
        <!-- Logo e Título -->
        <div class="text-center mb-8 sm:mb-10">
            <img
                src="<?= htmlspecialchars($faviconPath, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                alt="<?= htmlspecialchars($orgName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                class="inline-block w-14 h-14 mb-4 object-contain rounded-[5px]" />
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Redefinir Senha
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                Digite sua nova senha abaixo
            </p>
        </div>

        <!-- Card de Redefinição -->
        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-6 sm:p-8 space-y-6">
            <?php require VIEW_PATH . '/components/flash.php'; ?>

            <form action="/redefinir-senha" method="POST" class="space-y-5">
                <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">

                <!-- Nova Senha -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-200 block">
                        Nova Senha
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 dark:text-gray-500 pointer-events-none group-focus-within:text-[var(--primary-color)] transition">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </span>
                        <input 
                            type="password" 
                            name="nova_senha" 
                            required 
                            placeholder="••••••••"
                            minlength="6"
                            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm focus:outline-none focus:border-[var(--primary-color)] focus:ring-2 focus:ring-[color-mix(in_srgb,var(--primary-color)_20%,transparent)] dark:focus:ring-[color-mix(in_srgb,var(--primary-color)_30%,transparent)] transition"
                        >
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Mínimo 6 caracteres</p>
                </div>

                <!-- Confirmar Senha -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-200 block">
                        Confirmar Senha
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 dark:text-gray-500 pointer-events-none group-focus-within:text-[var(--primary-color)] transition">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </span>
                        <input 
                            type="password" 
                            name="confirma_senha" 
                            required 
                            placeholder="••••••••"
                            minlength="6"
                            class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm focus:outline-none focus:border-[var(--primary-color)] focus:ring-2 focus:ring-[color-mix(in_srgb,var(--primary-color)_20%,transparent)] dark:focus:ring-[color-mix(in_srgb,var(--primary-color)_30%,transparent)] transition"
                        >
                    </div>
                </div>

                <!-- Botão Redefinir -->
                <button 
                    type="submit"
                    class="w-full py-3 px-4 rounded-2xl bg-[var(--primary-color)] text-white font-semibold text-sm sm:text-base shadow-lg hover:shadow-xl hover:bg-[var(--primary-color-hover)] focus:outline-none focus:ring-2 focus:ring-[color-mix(in_srgb,var(--primary-color)_30%,transparent)] transition flex items-center justify-center gap-2"
                >
                    <i data-lucide="check" class="w-5 h-5"></i>
                    Redefinir Senha
                </button>
            </form>

            <!-- Link -->
            <div class="text-center text-sm">
                <a 
                    href="/entrar" 
                    class="text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)] dark:text-[color-mix(in_srgb,var(--primary-color)_80%,white_20%)] hover:text-[var(--primary-color)] dark:hover:text-[color-mix(in_srgb,var(--primary-color)_90%,white_10%)] transition font-medium"
                >
                    Voltar para Login
                </a>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                2024 FerramentasAi. Todos os direitos reservados.
            </p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/base.php';
?>
