<?php
// /app/Views/admin/parceiros/relatorio.php
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
                    Relatório do parceiro
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                    <?= htmlspecialchars($parceiro['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Período <?= htmlspecialchars($periodo['inicio'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> a <?= htmlspecialchars($periodo['fim'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/parceiros/<?= (int)($parceiro['id'] ?? 0) ?>/ver" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar PDF
                </button>
                <button class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="table" class="w-4 h-4"></i>
                    Exportar Excel
                </button>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Transferências</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($totais['transferencias'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Itens enviados</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Vendas</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($totais['vendas'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Itens vendidos</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Devoluções</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($totais['devolucoes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Itens retornados</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Faturado estimado</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= htmlspecialchars($totais['faturado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Período selecionado</p>
        </div>
    </div>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Top produtos</h3>
                <div class="text-xs text-gray-500 dark:text-gray-400">Ranking por faturamento</div>
            </div>
            <div class="overflow-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-3 py-2">Produto</th>
                            <th class="px-3 py-2">SKU</th>
                            <th class="px-3 py-2">Vendas</th>
                            <th class="px-3 py-2">Faturado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <?php foreach ($topProdutos as $item): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                                <td class="px-3 py-2 font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($item['produto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($item['sku'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($item['vendas'] ?? 0) ?></td>
                                <td class="px-3 py-2 text-gray-900 dark:text-white"><?= htmlspecialchars($item['faturado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Filtros</h3>
                <span class="text-[11px] text-gray-500 dark:text-gray-400">Período fechado</span>
            </div>
            <div class="space-y-2 text-xs text-gray-600 dark:text-gray-300">
                <div class="flex items-center justify-between rounded-xl border border-gray-100 dark:border-gray-800 px-3 py-2 bg-gray-50 dark:bg-gray-800/60">
                    <span>Período</span>
                    <span class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($periodo['inicio'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars($periodo['fim'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-gray-100 dark:border-gray-800 px-3 py-2 bg-gray-50 dark:bg-gray-800/60">
                    <span>Parceiro</span>
                    <span class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($parceiro['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-gray-100 dark:border-gray-800 px-3 py-2 bg-gray-50 dark:bg-gray-800/60">
                    <span>Filtros extras</span>
                    <span class="text-gray-500 dark:text-gray-400">Não aplicados</span>
                </div>
            </div>
            <div class="space-y-1 text-xs text-gray-500 dark:text-gray-400">
                <p>Inclui dados de transferências, vendas, devoluções e ajustes no período.</p>
                <p>Valores estimados podem variar se houver ajustes posteriores.</p>
            </div>
        </div>
    </div>

    <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Movimentações no período</h3>
            <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs">Exportar CSV</button>
        </div>
        <div class="space-y-2 max-h-80 overflow-auto">
            <?php foreach ($movimentacoes as $mov): ?>
                <div class="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/60 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($mov['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($mov['descricao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    </div>
                    <div class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                        <?= (int)($mov['quantidade'] ?? 0) ?>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400"><?= htmlspecialchars($mov['valor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400"><?= htmlspecialchars($mov['data'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
