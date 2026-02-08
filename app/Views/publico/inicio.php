<?php
use App\Core\Auth;
use App\Core\Database;

$title = null;
$slogan = 'Plataforma de Gestão Inteligente';
$description = $slogan;
$user = Auth::user();

$orgName = 'FerramentasAi';
$logoLightSource = null;
$logoDarkSource = null;
$fallbackLogoLightPath = '/uploads/fallback-images/logo-light-fallback.svg';
$fallbackLogoDarkPath = '/uploads/fallback-images/logo-dark-fallback.svg';
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

try {
    $pdo = Database::getConnection();
    $settingsTable = Database::table('settings');
    $stmt = $pdo->query("SELECT org_name, slogan, logo_light_path, logo_dark_path FROM {$settingsTable} LIMIT 1");
    $settingsRow = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    if (!empty($settingsRow['org_name'])) {
        $orgName = $settingsRow['org_name'];
    }
    if (!empty($settingsRow['slogan'])) {
        $slogan = $settingsRow['slogan'];
        $description = $slogan;
    }
    if (!empty($settingsRow['logo_light_path'])) {
        $logoLightSource = $settingsRow['logo_light_path'];
    }
    if (!empty($settingsRow['logo_dark_path'])) {
        $logoDarkSource = $settingsRow['logo_dark_path'];
    }
} catch (\Throwable $e) {
    // silenciosamente mantém defaults
}
$hasCustomLogo = (bool) ($logoLightSource || $logoDarkSource);
$title = $orgName . ' - ' . $slogan;
$logoForLight = $logoLightSource ?: $fallbackLogoLightPath;
$logoForDark = $logoDarkSource ?: $fallbackLogoDarkPath;
?>

<?php
$content = '';
ob_start();
?>

<!-- Hero Section -->
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">
    <!-- Navigation -->
    <nav class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-8 flex items-center">
                    <!-- Light mode usa logo clara (fallback incluso) -->
                    <img
                        src="<?= $escape($logoForLight) ?>"
                        alt="<?= $escape($orgName) ?>"
                        class="h-8 w-auto object-contain dark:hidden" />
                    <!-- Dark mode usa logo escura (fallback incluso) -->
                    <img
                        src="<?= $escape($logoForDark) ?>"
                        alt="<?= $escape($orgName) ?>"
                        class="h-8 w-auto object-contain hidden dark:inline-block" />
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    data-theme-toggle
                    aria-pressed="false"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm font-semibold">
                    <span class="sr-only">Alternar tema</span>
                    <span class="flex items-center gap-1 dark:hidden">
                        <i data-lucide="sun" class="w-4 h-4"></i>
                        <span class="text-xs font-semibold text-gray-600">Claro</span>
                    </span>
                    <span class="hidden items-center gap-1 dark:flex">
                        <i data-lucide="moon" class="w-4 h-4"></i>
                        <span class="text-xs font-semibold text-gray-200">Escuro</span>
                    </span>
                </button>
                <?php if ($user): ?>
                    <a href="<?= $user->role === 'admin' ? '/admin' : ($user->role === 'colaborador' ? '/colaborador' : '/cliente') ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)] dark:text-[color-mix(in_srgb,var(--primary-color)_80%,white_20%)] hover:bg-[color-mix(in_srgb,var(--primary-color)_18%,white)] dark:hover:bg-[color-mix(in_srgb,var(--primary-color)_20%,transparent)] transition text-sm font-semibold">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Painel
                    </a>
                    <a href="/sair" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm font-semibold">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Sair
                    </a>
                <?php else: ?>
                    <a href="/entrar" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-sm font-semibold">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Entrar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Content -->
    <div class="flex-1 flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 py-20 sm:py-32">
        <div class="text-center max-w-4xl mx-auto space-y-8">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 rounded-full px-4 py-2 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] border border-[color-mix(in_srgb,var(--primary-color)_30%,white)] dark:border-[color-mix(in_srgb,var(--primary-color)_30%,transparent)]">
                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)] dark:text-[color-mix(in_srgb,var(--primary-color)_80%,white_20%)]">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    Bem-vindo à plataforma
                </span>
            </div>

            <!-- Heading -->
            <div class="space-y-4">
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-gray-900 dark:text-white leading-tight">
                    Gestão Inteligente
                    <span class="block bg-gradient-to-r from-[var(--primary-color)] to-[color-mix(in_srgb,var(--primary-color)_70%,blue_30%)] bg-clip-text text-transparent">
                        para seu negócio
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                    Plataforma completa com suporte integrado, gerenciamento de usuários e configurações avançadas. Tudo que você precisa em um único lugar.
                </p>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                <?php if (!$user): ?>
                    <a href="/entrar" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-[var(--primary-color)] text-white font-semibold shadow-lg hover:shadow-xl hover:bg-[var(--primary-color-hover)] transition w-full sm:w-auto">
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        Começar agora
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl border-2 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition w-full sm:w-auto">
                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                        Saiba mais
                    </a>
                <?php else: ?>
                    <a href="<?= $user->role === 'admin' ? '/admin' : ($user->role === 'colaborador' ? '/colaborador' : '/cliente') ?>" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-[var(--primary-color)] text-white font-semibold shadow-lg hover:shadow-xl hover:bg-[var(--primary-color-hover)] transition w-full sm:w-auto">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        Ir para o painel
                    </a>
                <?php endif; ?>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 pt-12 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-[var(--primary-color)]">100%</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Responsivo</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-[var(--primary-color)]">24/7</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Disponível</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-bold text-[var(--primary-color)]">∞</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Escalável</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section id="features" class="py-20 sm:py-32 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20">
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Recursos Poderosos
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                Tudo que você precisa para gerenciar seu negócio de forma eficiente
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Feature 1 -->
            <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 hover:border-[color-mix(in_srgb,var(--primary-color)_45%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_45%,transparent)] hover:shadow-lg dark:hover:shadow-lg dark:hover:shadow-gray-900/50 transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[var(--primary-color)]">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Gestão de Usuários
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Controle completo sobre usuários, roles e permissões com interface intuitiva.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 hover:border-[color-mix(in_srgb,var(--primary-color)_45%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_45%,transparent)] hover:shadow-lg dark:hover:shadow-lg dark:hover:shadow-gray-900/50 transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[var(--primary-color)]">
                    <i data-lucide="briefcase" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Projetos e Entregas
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Organize demandas, acompanhe prazos e comunique-se com a equipe em um só lugar.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 hover:border-[color-mix(in_srgb,var(--primary-color)_45%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_45%,transparent)] hover:shadow-lg dark:hover:shadow-lg dark:hover:shadow-gray-900/50 transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[var(--primary-color)]">
                    <i data-lucide="settings" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Configurações Avançadas
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Personalize cores, logos e informações da sua organização facilmente.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 hover:border-[color-mix(in_srgb,var(--primary-color)_45%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_45%,transparent)] hover:shadow-lg dark:hover:shadow-lg dark:hover:shadow-gray-900/50 transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[var(--primary-color)]">
                    <i data-lucide="moon" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Tema Claro/Escuro
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Suporte completo para temas light e dark com preferência por usuário.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 hover:border-[color-mix(in_srgb,var(--primary-color)_45%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_45%,transparent)] hover:shadow-lg dark:hover:shadow-lg dark:hover:shadow-gray-900/50 transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[var(--primary-color)]">
                    <i data-lucide="shield" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Segurança
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Proteção CSRF, sanitização de dados e autenticação segura por sessão.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 sm:p-8 hover:border-[color-mix(in_srgb,var(--primary-color)_45%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_45%,transparent)] hover:shadow-lg dark:hover:shadow-lg dark:hover:shadow-gray-900/50 transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[var(--primary-color)]">
                    <i data-lucide="smartphone" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Responsivo
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Design mobile-first que funciona perfeitamente em qualquer dispositivo.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 sm:py-32 bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mb-6">
            Pronto para começar?
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
            Junte-se a milhares de usuários que já estão usando nossa plataforma para gerenciar seus negócios.
        </p>
        <?php if (!$user): ?>
            <a href="/entrar" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-[var(--primary-color)] text-white font-semibold shadow-lg hover:shadow-xl hover:bg-[var(--primary-color-hover)] transition">
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                Acessar plataforma
            </a>
        <?php else: ?>
            <a href="<?= $user->role === 'admin' ? '/admin' : ($user->role === 'colaborador' ? '/colaborador' : '/cliente') ?>" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-[var(--primary-color)] text-white font-semibold shadow-lg hover:shadow-xl hover:bg-[var(--primary-color-hover)] transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Voltar ao painel
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i data-lucide="hexagon" class="w-5 h-5 text-[var(--primary-color)]"></i>
                <span class="text-sm text-gray-600 dark:text-gray-400">© 2024 FerramentasAi. Todos os direitos reservados.</span>
            </div>
            <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                <a href="#" class="hover:text-[var(--primary-color)] transition">Privacidade</a>
                <a href="#" class="hover:text-[var(--primary-color)] transition">Termos</a>
                <a href="#" class="hover:text-[var(--primary-color)] transition">Contato</a>
            </div>
        </div>
    </div>
</footer>

<?php
$themeToggleScript = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.querySelector('[data-theme-toggle]');
  if (!toggleBtn) {
    return;
  }
  const syncState = () => {
    const isDark = document.documentElement.classList.contains('dark');
    toggleBtn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
  };
  toggleBtn.addEventListener('click', () => {
    const isDark = document.documentElement.classList.contains('dark');
    const next = isDark ? 'light' : 'dark';
    if (typeof window.__setThemePreference === 'function') {
      window.__setThemePreference(next);
    } else {
      document.documentElement.classList.toggle('dark', next === 'dark');
    }
    syncState();
  });
  syncState();
});
</script>
HTML;

$scripts = ($scripts ?? '') . $themeToggleScript;

$content = ob_get_clean();
require VIEW_PATH . '/layouts/base.php';
?>
