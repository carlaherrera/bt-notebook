<?php
/**
 * View: Componente Sidebar
 * 
 * Menu lateral reutilizável para diferentes painéis.
 * Adaptado para o contexto do projeto com suporte a roles e customização.
 */

use App\Core\Auth;
use App\Core\Database;

$user = Auth::user();
$role = $user?->role ?? null;
$displayName = trim(($user->nome ?? '') . ' ' . ($user->sobrenome ?? '')) ?: 'Usuário';
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$escape = static fn(string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// Fallback de role quando ainda não há login implementado
if (!$role) {
    if (strpos($currentPath, '/admin') === 0) {
        $role = 'admin';
    } elseif (strpos($currentPath, '/colaborador') === 0) {
        $role = 'colaborador';
    } elseif (strpos($currentPath, '/cliente') === 0) {
        $role = 'cliente';
    } else {
        $role = 'guest';
    }
}

$panelInfo = [
    'admin' => [
        'title' => 'Admin Panel',
        'subtitle' => 'Dashboard',
        'home' => '/admin',
    ],
    'colaborador' => [
        'title' => 'Colaborador',
        'subtitle' => 'Painel',
        'home' => '/colaborador',
    ],
    'cliente' => [
        'title' => 'Cliente',
        'subtitle' => 'Painel',
        'home' => '/cliente',
    ],
];

$menus = [
    'admin' => [
        ['label' => 'Painel', 'icon' => 'layout-dashboard', 'href' => '/admin', 'match' => '/admin'],
        ['label' => 'Parceiros', 'icon' => 'handshake', 'href' => '/admin/parceiros', 'match' => '/admin/parceiros'],
        ['label' => 'Consignado', 'icon' => 'truck', 'href' => '/admin/consignado', 'match' => '/admin/consignado'],
        ['label' => 'Produtos', 'icon' => 'package', 'href' => '/admin/produtos', 'match' => '/admin/produtos'],
        ['label' => 'Movimentações', 'icon' => 'shuffle', 'href' => '/admin/movimentacoes', 'match' => '/admin/movimentacoes'],
        ['label' => 'Relatórios', 'icon' => 'file-text', 'href' => '/admin/relatorios', 'match' => '/admin/relatorios'],
        ['label' => 'Auditoria', 'icon' => 'check-circle', 'href' => '/admin/auditoria', 'match' => '/admin/auditoria'],
        ['label' => 'Perfil', 'icon' => 'user', 'href' => '/admin/perfil', 'match' => '/admin/perfil'],
        ['label' => 'Usuários', 'icon' => 'users', 'href' => '/admin/usuarios', 'match' => '/admin/usuarios'],
        ['label' => 'Configurações', 'icon' => 'settings', 'href' => '/admin/configuracoes', 'match' => '/admin/configuracoes'],
    ],
    'colaborador' => [
        ['label' => 'Painel', 'icon' => 'layout-dashboard', 'href' => '/colaborador', 'match' => '/colaborador'],
        ['label' => 'Perfil', 'icon' => 'user', 'href' => '/colaborador/perfil', 'match' => '/colaborador/perfil'],
    ],
    'cliente' => [
        ['label' => 'Painel', 'icon' => 'layout-dashboard', 'href' => '/cliente', 'match' => '/cliente'],
        ['label' => 'Pedidos', 'icon' => 'shopping-bag', 'href' => '/cliente/pedidos', 'match' => '/cliente/pedidos'],
        ['label' => 'Suporte', 'icon' => 'life-buoy', 'href' => '/cliente/suporte', 'match' => '/cliente/suporte'],
        ['label' => 'Perfil', 'icon' => 'user', 'href' => '/cliente/perfil', 'match' => '/cliente/perfil'],
        ['label' => 'Endereços', 'icon' => 'map-pin', 'href' => '/cliente/enderecos', 'match' => '/cliente/enderecos'],
        ['label' => 'Notas Fiscais', 'icon' => 'file-text', 'href' => '/cliente/notas', 'match' => '/cliente/notas'],
    ],
];

$isActive = function($path) use ($currentPath) {
    return $currentPath === $path;
};

$colorScheme = 'brand'; // Pode ser customizado via painel futuramente

$faviconFallback = '/uploads/fallback-images/favicon-fallback.svg';
$faviconPath = $faviconFallback;
$logoLightPath = '/uploads/fallback-images/logo-light-fallback.svg';
$logoDarkPath = '/uploads/fallback-images/logo-dark-fallback.svg';
$orgName = 'FerramentasAi';
$avatarFallback = '/uploads/fallback-images/fallback-avatar.webp';
$userAvatar = \App\Core\Security::sanitizeString($user?->imagem_perfil ?? $avatarFallback);

try {
    $pdo = Database::getConnection();
    $settingsTable = Database::table('settings');
    $settingsRow = $pdo->query('SELECT org_name, favicon_path, logo_light_path, logo_dark_path FROM ' . $settingsTable . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);
    if (!empty($settingsRow['favicon_path'])) {
        $faviconPath = $settingsRow['favicon_path'];
    }
    if (!empty($settingsRow['logo_light_path'])) {
        $logoLightPath = $settingsRow['logo_light_path'];
    }
    if (!empty($settingsRow['logo_dark_path'])) {
        $logoDarkPath = $settingsRow['logo_dark_path'];
    }
    if (!empty($settingsRow['org_name'])) {
        $orgName = $settingsRow['org_name'];
    }
} catch (\Throwable $e) {
    // mantém fallback
}
?>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-[260px] bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform -translate-x-full lg:translate-x-0 lg:static transition-all duration-300 flex flex-col">

    <!-- Logo -->
    <div class="pt-3 pb-1 border-b border-gray-200 dark:border-gray-700">
        <a href="<?= $panelInfo[$role]['home'] ?? '/' ?>" class="inline-flex items-center justify-center w-full">
            <img src="<?= $escape($logoLightPath) ?>"
                 alt="<?= $escape($orgName) ?>"
                 class="block dark:hidden w-[120px] h-auto object-contain" />
            <img src="<?= $escape($logoDarkPath) ?>"
                 alt="<?= $escape($orgName) ?>"
                 class="hidden dark:block w-[120px] h-auto object-contain" />
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-700">

        <?php $items = $menus[$role] ?? []; ?>

        <?php if (!empty($items)): ?>
            <div class="mb-4">
                <p class="px-3 mb-2 text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Menu
                </p>

                <?php foreach ($items as $item): ?>
                    <a href="<?= $item['href'] ?>" class="nav-link <?= $isActive($item['match']) ? 'active' : '' ?>">
                        <i data-lucide="<?= $item['icon'] ?>" class="w-[18px] h-[18px]"></i>
                        <span><?= $item['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </nav>

    <!-- User Footer -->
    <div class="p-3 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors cursor-pointer group">
            <!-- Avatar com status -->
            <div class="relative">
                <div class="w-9 h-9 rounded-full overflow-hidden ring-2 ring-white dark:ring-gray-800 bg-white">
                    <img src="<?= $userAvatar ?>" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
            </div>
            
            <!-- Info -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    <?= $displayName ?? 'Administrador' ?>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    <?= $user?->email ?? 'admin@exemplo.com.br' ?>
                </p>
            </div>
            
            <!-- Menu dropdown trigger -->
            <div class="relative" onclick="event.stopPropagation()">
                <button onclick="toggleSidebarUserMenu(event)" class="w-full h-full p-2 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center">
                    <i data-lucide="more-vertical" class="w-4 h-4 pointer-events-none"></i>
                </button>
                
                <!-- User dropdown menu -->
                <div id="sidebarUserMenu" class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 hidden">
                    <?php
                        $perfilPath = $role === 'admin' ? '/admin/perfil' : ($role === 'colaborador' ? '/colaborador/perfil' : ($role === 'cliente' ? '/cliente/perfil' : '/perfil'));
                        $prefPath = $role === 'admin' ? '/admin/configuracoes' : ($role === 'colaborador' ? '/colaborador/preferencias' : ($role === 'cliente' ? '/cliente/preferencias' : '/preferencias'));
                    ?>
                    <a href="<?= $perfilPath ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[rgba(0,0,0,0.06)] dark:hover:bg-[rgba(255,255,255,0.08)] hover:text-[#111827] dark:hover:text-[#f9fafb]">
                        <i data-lucide="user-circle" class="w-4 h-4"></i>
                        <span>Meu Perfil</span>
                    </a>
                    <a href="<?= $prefPath ?>" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[rgba(0,0,0,0.06)] dark:hover:bg-[rgba(255,255,255,0.08)] hover:text-[#111827] dark:hover:text-[#f9fafb]">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        <span>Preferências</span>
                    </a>
                    <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[rgba(0,0,0,0.06)] dark:hover:bg-[rgba(255,255,255,0.08)] hover:text-[#111827] dark:hover:text-[#f9fafb]">
                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                        <span>Ajuda</span>
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <a href="/sair" class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-[rgba(0,0,0,0.06)] dark:hover:bg-[rgba(255,255,255,0.08)] hover:text-[#991b1b] dark:hover:text-[#fecaca]">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</aside>

<!-- Overlay mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<style>
    /* Scrollbar customizado */
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        border-radius: 3px;
    }

    /* Nav Links */
    .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        color: #374151;
        border-radius: 0.5rem;
        transition: all 0.15s ease;
        cursor: pointer;
        text-decoration: none;
        border: none;
        background: none;
        width: auto;
        margin-bottom: 0.35rem;
    }

    .nav-link:hover {
        background-color: #f3f4f6;
        color: #111827;
    }

    .dark .nav-link {
        color: #d1d5db;
    }

    .dark .nav-link:hover {
        background-color: rgba(55, 65, 81, 0.5);
        color: #f3f4f6;
    }

    .nav-link.active {
        background: rgba(0, 0, 0, 0.06);
        color: #111827;
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .dark .nav-link.active {
        background: rgba(255, 255, 255, 0.08);
        color: #f9fafb;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.35);
    }

    /* Icons in nav-link */
    .nav-link i[data-lucide],
    .nav-link svg {
        flex-shrink: 0;
        width: 1.125rem;
        height: 1.125rem;
        display: inline-block;
    }

    /* Dropdowns */
    .dropdown-menu {
        display: none;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-arrow {
        transition: transform 0.2s ease;
    }

    .dropdown.open .dropdown-arrow {
        transform: rotate(180deg);
    }
</style>

<script>
    // Toggle sidebar (mobile)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
    
    // Toggle dropdown menus
    function toggleDropdown(button) {
        const dropdown = button.closest('.dropdown');
        const menu = dropdown.querySelector('.dropdown-menu');
        const arrow = dropdown.querySelector('.dropdown-arrow');
        
        menu.classList.toggle('show');
        arrow.style.transform = menu.classList.contains('show') ? 'rotate(180deg)' : '';
        
        // Reinitialize Lucide icons after dropdown toggle
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
    
    // Toggle user menu (sidebar footer)
    function toggleSidebarUserMenu(event) {
        if (event) event.stopPropagation();
        const menu = document.getElementById('sidebarUserMenu');
        if (!menu) return;
        menu.classList.toggle('hidden');
        
        // Reinitialize Lucide icons after menu toggle
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
    
    // Close user menu when clicking outside
    document.addEventListener('click', function(e) {
        const userMenu = document.getElementById('sidebarUserMenu');
        const trigger = document.querySelector('[onclick="toggleSidebarUserMenu()"]');
        if (!userMenu) return;
        if (!e.target.closest('#sidebarUserMenu') && !e.target.closest('[onclick^="toggleSidebarUserMenu"]')) {
            userMenu.classList.add('hidden');
        }
    });
    
    // Initialize Lucide icons when sidebar loads
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
