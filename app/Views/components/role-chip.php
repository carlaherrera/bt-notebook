<?php
/**
 * Role Chip Component
 * @var string $role - Role do usuário (admin, colaborador, cliente)
 * @var string $size - Tamanho: 'sm' ou 'md' (padrão)
 */
$size = $size ?? 'md';
$sizeClass = $size === 'sm' 
    ? 'px-2 py-0.5 text-xs gap-0.5' 
    : 'px-3 py-1 text-xs gap-1';
?>
<span class="inline-flex items-center rounded-full border <?= $sizeClass ?> font-semibold text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700">
    <i data-lucide="badge-check" class="h-<?= $size === 'sm' ? '2.5' : '3.5' ?> w-<?= $size === 'sm' ? '2.5' : '3.5' ?>"></i>
    <?= $role ?>
</span>
