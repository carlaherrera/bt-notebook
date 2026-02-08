<?php
/**
 * Status Badge Component
 * @var int $status - 1 para ativo, 0 para inativo
 * @var string $label - Label customizado (opcional)
 */
$label = $label ?? ($status === 1 ? 'Ativo' : 'Inativo');
$statusClass = $status === 1 
    ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-200 dark:border-emerald-800' 
    : 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-900/30 dark:text-rose-200 dark:border-rose-800';
$icon = $status === 1 ? 'check-circle' : 'x-circle';
?>
<span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-semibold <?= $statusClass ?>">
    <i data-lucide="<?= $icon ?>" class="h-3.5 w-3.5"></i>
    <?= $label ?>
</span>
