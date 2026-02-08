<?php
// /app/Views/admin/auditoria/index.php
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
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Auditoria
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Conferência de estoque</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Pendências de contagem, divergências e ajustes aplicados.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                    Nova contagem
                </button>
                <button class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar auditoria
                </button>
            </div>
        </div>
    </header>

    <!-- Resumo -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Contagens pendentes</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['contagens_pendentes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Aguardando conferência</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Divergências</p>
            <p class="text-2xl font-bold text-amber-600 mt-1"><?= (int)($resumo['divergencias'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Itens com diferença</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Ajustes aplicados</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['ajustes_aplicados'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Correções realizadas</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Auditorias no mês</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['auditorias_mes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total executado</p>
        </div>
    </div>

    <!-- Checklist -->
    <div class="grid gap-4 grid-cols-1 xl:grid-cols-2">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Checklist de contagem</h3>
                <span class="text-[11px] text-gray-500 dark:text-gray-400">Priorizar divergências</span>
            </div>
            <div class="space-y-2">
                <?php foreach ($checklist as $item): 
                    $status = strtolower($item['status'] ?? 'pendente');
                    $color = $status === 'divergência' ? 'text-amber-600 bg-amber-50 border-amber-200 dark:bg-amber-900/40 dark:border-amber-800' : 'text-gray-700 bg-gray-50 border-gray-200 dark:bg-gray-800/60 dark:border-gray-700';
                ?>
                <div class="p-3 rounded-xl border <?= $color ?> flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($item['produto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400"><?= htmlspecialchars($item['local'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    </div>
                    <div class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                        Sys: <?= (int)($item['qtde_sistema'] ?? 0) ?><br>
                        Fís: <?= $item['qtde_fisica'] === null ? '—' : (int)$item['qtde_fisica'] ?>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 capitalize"><?= htmlspecialchars($item['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Histórico -->
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Histórico recente</h3>
                <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs">Exportar</button>
            </div>
            <div class="space-y-2 max-h-72 overflow-auto">
                <?php foreach ($historico as $h): ?>
                    <div class="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/60 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($h['acao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($h['descricao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Por <?= htmlspecialchars($h['usuario'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        </div>
                        <div class="text-right text-[11px] text-gray-500 dark:text-gray-400"><?= htmlspecialchars($h['data'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
