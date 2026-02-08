<?php
// /app/Views/admin/consignado/show.php
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
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    Consignado
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    <?= htmlspecialchars($parceiro['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                    <span class="text-[11px] px-2 py-[3px] rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                        <?= htmlspecialchars($parceiro['cidade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                    </span>
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Estoque consignado, vendas, devoluções e movimentações.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/consignado" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar
                </button>
                <button class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Transferir
                </button>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Itens consignados</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= array_sum(array_column($produtos, 'estoque')) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">No parceiro</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Itens em alerta</p>
            <p class="text-2xl font-bold text-amber-600 mt-1"><?= count(array_filter($produtos, fn($p) => ($p['estoque'] ?? 0) <= ($p['min'] ?? 0))) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Abaixo do mínimo</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Vendido no mês</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= array_sum(array_column($produtos, 'vendido_mes')) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Origem parceiro</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Próxima devolução</p>
            <p class="text-sm font-bold text-gray-900 dark:text-white mt-1"><?= htmlspecialchars($parceiro['prazo_devolucao'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">NF <?= htmlspecialchars($parceiro['nf'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        </div>
    </div>

    <div class="space-y-4">
        <!-- Produtos consignados -->
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Produtos consignados</h3>
                <div class="flex gap-2 text-xs">
                    <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Exportar</button>
                    <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Ajustar</button>
                </div>
            </div>
            <div class="overflow-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-3 py-2">Produto</th>
                            <th class="px-3 py-2">SKU</th>
                            <th class="px-3 py-2">Lote / NF</th>
                            <th class="px-3 py-2">Estoque</th>
                            <th class="px-3 py-2">Mín.</th>
                            <th class="px-3 py-2">Vend. mês</th>
                            <th class="px-3 py-2">Devolução</th>
                            <th class="px-3 py-2">Prazo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <?php foreach ($produtos as $item): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                                <td class="px-3 py-2 font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($item['produto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($item['sku'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($item['lote'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars($item['nf'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </td>
                                <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($item['estoque'] ?? 0) ?></td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= (int)($item['min'] ?? 0) ?></td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= (int)($item['vendido_mes'] ?? 0) ?></td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= (int)($item['devolucao'] ?? 0) ?></td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($item['prazo_dev'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Movimentações</h3>
                <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs">Exportar</button>
            </div>
            <div class="space-y-2 max-h-72 overflow-auto">
                <?php foreach ($movimentacoes as $mov): ?>
                    <div class="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/60 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($mov['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($mov['descricao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        </div>
                        <div class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                            <?= (int)($mov['quantidade'] ?? 0) ?>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400"><?= htmlspecialchars($mov['data'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
