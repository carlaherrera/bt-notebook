<?php
use App\Core\Auth;

$user = Auth::user();
$role = $user?->role ?? 'visitante';
$nome = trim(($user->nome ?? '') . ' ' . ($user->sobrenome ?? '')) ?: 'Usuário';
$email = $user?->email ?? 'admin@exemplo.com.br';
$pageTitle = $title ?? 'Página';
$themePref = $themePreference ?? null; // opcional vindo do layout
$perfilPath = $role === 'admin' ? '/admin/perfil' : ($role === 'colaborador' ? '/colaborador/perfil' : ($role === 'cliente' ? '/cliente/perfil' : '/perfil'));
$prefPath = $role === 'admin' ? '/admin/configuracoes' : ($role === 'colaborador' ? '/colaborador/preferencias' : ($role === 'cliente' ? '/cliente/preferencias' : '/preferencias'));
$avatarFallback = '/uploads/fallback-images/fallback-avatar.webp';
$avatarSrc = $user?->imagem_perfil ?: $avatarFallback;
$avatarEsc = htmlspecialchars($avatarSrc, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>

<header class="sticky top-0 z-30 bg-white/90 dark:bg-gray-900/90 backdrop-blur border-b border-gray-200 dark:border-gray-800">
    <div class="px-4 sm:px-5 py-2.5 flex items-center justify-between gap-3">

        <div class="flex flex-col leading-tight">
            <span class="text-[10px] uppercase text-gray-400 dark:text-gray-500 font-semibold">Bem-vinda de volta</span>
            <h1 class="text-sm font-semibold text-gray-900 dark:text-white"><?= $pageTitle ?></h1>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="toggleSidebar()" class="p-2 rounded-full border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors sm:hidden" aria-label="Abrir menu">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <button id="theme-toggle" class="p-2 rounded-full border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="Alternar tema">
                <i data-lucide="sun" class="w-5 h-5 dark:hidden"></i>
                <i data-lucide="moon" class="w-5 h-5 hidden dark:inline"></i>
            </button>

            <div class="relative">
                <button onclick="toggleUserDropdown()" class="flex items-center gap-2 pl-2 pr-2 border border-gray-200 dark:border-gray-800 rounded-full py-1 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="relative">
                        <img 
                            src="<?= $avatarEsc ?>" 
                            alt="<?= $nome ?>"
                            class="w-9 h-9 rounded-full object-cover shadow-sm"
                        />
                        <span class="absolute bottom-0 right-0 w-2 h-2 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full"></span>
                    </div>
                    <div class="hidden sm:block text-left min-w-[120px]">
                        <p class="text-[13px] font-semibold text-gray-900 dark:text-white leading-tight"><?= $nome ?></p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate"><?= $email ?></p>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                </button>
                <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-lg py-2 hidden">
                    <a href="<?= $perfilPath ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:hover:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] hover:text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)]">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span>Perfil</span>
                    </a>
                    <a href="<?= $prefPath ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:hover:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] hover:text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)]">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        <span>Preferências</span>
                    </a>
                    <div class="border-t border-gray-200 dark:border-gray-800 my-1"></div>
                    <a href="/sair" class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:hover:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] hover:text-[color-mix(in_srgb,var(--primary-color)_70%,black_30%)]">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>

<script>
    function toggleUserDropdown() {
        const menu = document.getElementById('userDropdown');
        menu.classList.toggle('hidden');
    }

    document.addEventListener('click', (e) => {
        const menu = document.getElementById('userDropdown');
        if (!menu) return;
        if (!e.target.closest('#userDropdown') && !e.target.closest('[onclick="toggleUserDropdown()"]')) {
            menu.classList.add('hidden');
        }
    });

    // Alternar tema com persistência (localStorage + backend)
    (() => {
        const STORAGE_KEY = 'themePreference';
        const themeBtn = document.getElementById('theme-toggle');
        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = metaCsrf ? metaCsrf.getAttribute('content') : '';
        const serverPref = document.documentElement.dataset.serverTheme || 'system';
        const mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

        const getStored = () => {
            try { return localStorage.getItem(STORAGE_KEY); } catch { return null; }
        };
        const setStored = (pref) => {
            try { localStorage.setItem(STORAGE_KEY, pref); } catch { /* ignore */ }
        };

        const applyTheme = (pref) => {
            const nextIsDark = pref === 'dark' || (pref === 'system' && mediaQuery?.matches);
            document.documentElement.classList.toggle('dark', !!nextIsDark);
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        };

        const sendPreference = async (pref) => {
            if (!csrfToken) return;
            try {
                await fetch('/preferencias/tema', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `_csrf=${encodeURIComponent(csrfToken)}&theme_preference=${encodeURIComponent(pref)}`
                });
            } catch { /* silent */ }
        };

        let currentPref = getStored() || serverPref || 'system';
        applyTheme(currentPref);

        if (mediaQuery) {
            mediaQuery.addEventListener('change', () => {
                if (currentPref === 'system') {
                    applyTheme('system');
                }
            });
        }

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                // alterna entre light/dark mantendo option system se já estava setado manualmente
                const next = currentPref === 'dark' ? 'light' : 'dark';
                currentPref = next;
                setStored(next);
                applyTheme(next);
                sendPreference(next);
            });
        }
    })();
</script>
