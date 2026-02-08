<?php
/**
 * Action Button Component
 * @var string $action - Tipo de ação: 'view', 'edit', 'delete', 'toggle_on', 'toggle_off'
 * @var string $href - URL do link
 * @var string $title - Título do botão (tooltip)
 * @var string $size - Tamanho: 'sm' (8x8) ou 'md' (padrão)
 */
$action = $action ?? 'view';
$href = $href ?? '#';
$title = $title ?? ucfirst($action);
$size = $size ?? 'md';

$config = [
    'view' => [
        'icon' => 'eye',
        'color' => 'blue',
        'label' => 'Ver'
    ],
    'edit' => [
        'icon' => 'pencil',
        'color' => 'amber',
        'label' => 'Editar'
    ],
    'delete' => [
        'icon' => 'trash',
        'color' => 'rose',
        'label' => 'Excluir'
    ],
    'toggle_on' => [
        'icon' => 'unlock',
        'color' => 'emerald',
        'label' => 'Liberar'
    ],
    'toggle_off' => [
        'icon' => 'lock',
        'color' => 'rose',
        'label' => 'Bloquear'
    ]
];

$btn = $config[$action] ?? $config['view'];
$sizeClass = 'h-8 w-8';

// Cores dinâmicas usando Tailwind
$colorMap = [
    'blue' => ['bg' => 'bg-blue-50', 'dark_bg' => 'dark:bg-blue-900/30', 'text' => 'text-blue-600', 'dark_text' => 'dark:text-blue-400', 'hover_bg' => 'hover:bg-blue-100', 'dark_hover_bg' => 'dark:hover:bg-blue-900/40', 'dark_hover_text' => 'dark:hover:text-blue-300'],
    'amber' => ['bg' => 'bg-amber-50', 'dark_bg' => 'dark:bg-amber-900/30', 'text' => 'text-amber-600', 'dark_text' => 'dark:text-amber-400', 'hover_bg' => 'hover:bg-amber-100', 'dark_hover_bg' => 'dark:hover:bg-amber-900/40', 'dark_hover_text' => 'dark:hover:text-amber-300'],
    'rose' => ['bg' => 'bg-rose-50', 'dark_bg' => 'dark:bg-rose-900/30', 'text' => 'text-rose-600', 'dark_text' => 'dark:text-rose-400', 'hover_bg' => 'hover:bg-rose-100', 'dark_hover_bg' => 'dark:hover:bg-rose-900/40', 'dark_hover_text' => 'dark:hover:text-rose-300'],
    'emerald' => ['bg' => 'bg-emerald-50', 'dark_bg' => 'dark:bg-emerald-900/30', 'text' => 'text-emerald-600', 'dark_text' => 'dark:text-emerald-400', 'hover_bg' => 'hover:bg-emerald-100', 'dark_hover_bg' => 'dark:hover:bg-emerald-900/40', 'dark_hover_text' => 'dark:hover:text-emerald-300'],
    'slate' => ['bg' => 'bg-slate-100', 'dark_bg' => 'dark:bg-slate-800/60', 'text' => 'text-slate-700', 'dark_text' => 'dark:text-slate-200', 'hover_bg' => 'hover:bg-slate-200', 'dark_hover_bg' => 'dark:hover:bg-slate-700', 'dark_hover_text' => 'dark:hover:text-white'],
];

$colors = $colorMap[$btn['color']] ?? $colorMap['blue'];
$colorClass = implode(' ', $colors);
?>
<a href="<?= $href ?>" class="inline-flex items-center justify-center rounded-full <?= $sizeClass ?> <?= $colorClass ?> border border-transparent shadow-sm transition" title="<?= $title ?>">
    <i data-lucide="<?= $btn['icon'] ?>" class="h-4 w-4"></i>
</a>
