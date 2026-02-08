<?php
/**
 * Metrics Bar Component
 * Aceita inteiros ou arrays/listas (countable) para cada métrica.
 * @var int|array $total
 * @var int|array $admins
 * @var int|array $colabs
 * @var int|array $clientes
 * @var int|array $ativos
 * @var int|array $inativos
 */
$normalizeCount = function ($value) {
    if (is_array($value) || $value instanceof Countable) {
        return count($value);
    }
    return (int) ($value ?? 0);
};

$total = $normalizeCount($total ?? 0);
$admins = $normalizeCount($admins ?? 0);
$colabs = $normalizeCount($colabs ?? 0);
$clientes = $normalizeCount($clientes ?? 0);
$ativos = $normalizeCount($ativos ?? 0);
$inativos = $normalizeCount($inativos ?? 0);
?>
<section class="hidden sm:flex gap-2 items-center rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-sm">
    <div class="flex items-center gap-2 text-sm">
        <i data-lucide="users" class="h-4 w-4 text-blue-500"></i>
        <span class="text-slate-600 dark:text-slate-300"><span class="font-semibold text-slate-900 dark:text-white"><?= $total ?></span> total</span>
    </div>
    <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
    <div class="flex items-center gap-2 text-sm">
        <i data-lucide="shield" class="h-4 w-4 text-amber-500"></i>
        <span class="text-slate-600 dark:text-slate-300"><span class="font-semibold text-slate-900 dark:text-white"><?= $admins ?></span> admin</span>
    </div>
    <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
    <div class="flex items-center gap-2 text-sm">
        <i data-lucide="briefcase" class="h-4 w-4 text-emerald-500"></i>
        <span class="text-slate-600 dark:text-slate-300"><span class="font-semibold text-slate-900 dark:text-white"><?= $colabs ?></span> colab</span>
    </div>
    <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
    <div class="flex items-center gap-2 text-sm">
        <i data-lucide="user" class="h-4 w-4 text-blue-500"></i>
        <span class="text-slate-600 dark:text-slate-300"><span class="font-semibold text-slate-900 dark:text-white"><?= $clientes ?></span> cliente</span>
    </div>
    <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 ml-auto"></div>
    <div class="flex items-center gap-2 text-sm ml-2">
        <i data-lucide="check-circle" class="h-4 w-4 text-emerald-500"></i>
        <span class="text-slate-600 dark:text-slate-300"><span class="font-semibold text-slate-900 dark:text-white"><?= $ativos ?></span> ativo</span>
    </div>
    <div class="w-px h-4 bg-slate-200 dark:bg-slate-700"></div>
    <div class="flex items-center gap-2 text-sm">
        <i data-lucide="x-circle" class="h-4 w-4 text-rose-500"></i>
        <span class="text-slate-600 dark:text-slate-300"><span class="font-semibold text-slate-900 dark:text-white"><?= $inativos ?></span> inativo</span>
    </div>
</section>
