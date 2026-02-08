<?php
use App\Core\Security;
use App\Core\Database;

$title = 'Termos de Uso - FerramentasAi';
$description = 'Leia e aceite os termos de uso para continuar.';

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
    <div class="w-full max-w-3xl">
        <div class="text-center mb-8 sm:mb-10">
            <img
                src="<?= htmlspecialchars($faviconPath, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                alt="<?= htmlspecialchars($orgName, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                class="inline-block w-14 h-14 mb-4 object-contain rounded-[5px]" />
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Termos de Uso
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                Leia e aceite para continuar usando a plataforma.
            </p>
        </div>

        <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-6 sm:p-8 space-y-6">
            <?php require VIEW_PATH . '/components/flash.php'; ?>

            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-200 space-y-4">
                <p><strong>1. Uso da Plataforma</strong><br>Ao continuar, você concorda em utilizar a plataforma conforme as políticas internas e legislação aplicável.</p>
                <p><strong>2. Privacidade</strong><br>Seus dados são tratados conforme nossa política de privacidade e apenas para finalidades legítimas do serviço.</p>
                <p><strong>3. Segurança</strong><br>Mantenha suas credenciais em sigilo e notifique-nos em caso de suspeita de uso indevido.</p>
                <p><strong>4. Conteúdo</strong><br>Não publique conteúdo ilegal, ofensivo ou que infrinja direitos de terceiros.</p>
                <p><strong>5. Alterações</strong><br>Os termos podem ser atualizados. Notificaremos mudanças relevantes.</p>
            </div>

            <form action="/termos" method="POST" class="space-y-4">
                <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="aceito" name="aceito" required class="mt-1 rounded border-gray-300 dark:border-gray-600 text-[var(--primary-color)] focus:ring-[var(--primary-color)] focus:ring-offset-0">
                    <label for="aceito" class="text-sm text-gray-700 dark:text-gray-300">Eu li e aceito os termos de uso.</label>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-2xl bg-[var(--primary-color)] text-white font-semibold shadow-lg hover:shadow-xl hover:bg-[var(--primary-color-hover)] focus:outline-none focus:ring-2 focus:ring-[color-mix(in_srgb,var(--primary-color)_30%,transparent)] transition">
                        Aceitar e continuar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/base.php';
?>
