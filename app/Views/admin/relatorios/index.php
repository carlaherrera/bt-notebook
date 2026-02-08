<?php
// /app/Views/admin/relatorios/index.php
?>

<style>
    :root {
        --primary-50: color-mix(in srgb, var(--primary-color) 12%, white);
        --primary-100: color-mix(in srgb, var(--primary-color) 20%, white);
        --primary-200: color-mix(in srgb, var(--primary-color) 32%, white);
        --primary-500: var(--primary-color);
        --primary-600: color-mix(in srgb, var(--primary-color) 85%, black 15%);
        --primary-700: color-mix(in srgb, var(--primary-color) 85%, black 15%);
    }
</style>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Relatórios
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Central de relatórios</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Exporte PDFs ou Excel com filtros simulados.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Ajustar filtros
                </button>
                <button class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar tudo
                </button>
            </div>
        </div>
    </header>

    <!-- Filtros aplicados -->
    <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-wrap gap-2 text-xs text-gray-600 dark:text-gray-300">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
            <i data-lucide="calendar-range" class="w-4 h-4"></i>
            <?= htmlspecialchars($filtros['periodo'] ?? 'Período', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
            <i data-lucide="building" class="w-4 h-4"></i>
            Parceiro: <?= htmlspecialchars($filtros['parceiro'] ?? 'Todos', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
            <i data-lucide="package" class="w-4 h-4"></i>
            Produto: <?= htmlspecialchars($filtros['produto'] ?? 'Todos', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
            <i data-lucide="file" class="w-4 h-4"></i>
            Saída: <?= htmlspecialchars($filtros['saida'] ?? 'PDF', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
    </div>

    <!-- Lista -->
    <div class="grid gap-4 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($relatorios as $rel): ?>
            <div class="p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm flex flex-col gap-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($rel['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($rel['descricao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Formatos: <?= htmlspecialchars($rel['formato'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    </div>
                    <span class="text-[11px] px-2 py-[3px] rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">Último: <?= htmlspecialchars($rel['ultima'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                </div>
                <div class="flex gap-2 text-xs">
                    <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">PDF</button>
                    <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Excel</button>
                    <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover-bg-gray-800">CSV</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
