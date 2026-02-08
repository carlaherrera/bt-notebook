<?php
/**
 * Custom Select Component
 * @var string $id - ID do select
 * @var string $icon - Ícone Lucide (ex: 'shield', 'circle-dot')
 * @var string $label - Label padrão
 * @var array $options - Array de opções [['value' => '', 'label' => 'Todas', 'icon' => 'shield'], ...]
 */
$id = $id ?? 'select-' . uniqid();
$icon = $icon ?? 'settings';
$label = $label ?? 'Selecione';
$options = $options ?? [];
?>
<div class="relative flex-1">
    <select id="<?= $id ?>" class="hidden" aria-hidden="true" tabindex="-1">
        <?php foreach ($options as $opt): ?>
            <option value="<?= $opt['value'] ?? '' ?>"><?= $opt['label'] ?? '' ?></option>
        <?php endforeach; ?>
    </select>
    <button type="button" data-select-trigger="<?= $id ?>" class="w-full inline-flex items-center justify-between rounded-2xl px-4 py-3 text-sm bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-50 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:shadow-md dark:hover:shadow-slate-900/30 transition">
        <div class="flex items-center gap-3">
            <i data-lucide="<?= $icon ?>" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
            <span class="truncate font-medium" data-select-label="<?= $id ?>"><?= $label ?></span>
        </div>
        <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 dark:text-slate-500 flex-shrink-0"></i>
    </button>
    <div data-select-menu="<?= $id ?>" class="absolute top-full left-0 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl py-2 hidden z-10">
        <?php foreach ($options as $opt): ?>
            <button type="button" data-select-option="<?= $id ?>" data-value="<?= $opt['value'] ?? '' ?>" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-700/50 transition">
                <?php if (isset($opt['icon'])): ?>
                    <i data-lucide="<?= $opt['icon'] ?>" class="h-4 w-4 text-slate-400"></i>
                <?php endif; ?>
                <span><?= $opt['label'] ?? '' ?></span>
            </button>
        <?php endforeach; ?>
    </div>
</div>
