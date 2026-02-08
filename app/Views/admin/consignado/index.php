<?php
// /app/Views/admin/consignado/index.php
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
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Estoque consignado por parceiro</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Visão consolidada de itens enviados, alertas e devoluções.</p>
            </div>
            <?php $firstPartnerId = (int)($parceiros[0]['id'] ?? 0); ?>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/consignado" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors">
                    <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                    Atualizar lista
                </a>
                <?php if ($firstPartnerId): ?>
                    <a href="/admin/consignado/parceiro/<?= $firstPartnerId ?>/ver" class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition-colors">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Transferir estoque
                    </a>
                <?php else: ?>
                    <span class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-400 dark:text-stone-500">Sem parceiros</span>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Resumo -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Itens consignados</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['total_itens'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total distribuído</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Itens em alerta</p>
            <p class="text-2l font-bold text-amber-600 mt-1"><?= (int)($resumo['itens_alerta'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Abaixo do mínimo</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Vendas no mês</p>
            <p class="text-2l font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['vendas_mes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Parceiros</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Transferências no mês</p>
            <p class="text-2l font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['transferencias_mes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Loja → parceiros</p>
        </div>
    </div>

    <!-- Tabela consolidada -->
    <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Parceiros</h3>
            <div class="flex gap-2 text-xs">
                <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Exportar</button>
            </div>
        </div>
        <div class="overflow-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-3 py-2">Parceiro</th>
                        <th class="px-3 py-2">Cidade</th>
                        <th class="px-3 py-2">Itens</th>
                        <th class="px-3 py-2">Em alerta</th>
                        <th class="px-3 py-2">Devolução</th>
                        <th class="px-3 py-2">Vendas (mês)</th>
                        <th class="px-3 py-2">Transferências (mês)</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php foreach ($parceiros as $p): 
                $id = (int)($p['id'] ?? 0);
            ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                    <td class="px-3 py-2 font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($p['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                    <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($p['cidade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                    <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($p['itens'] ?? 0) ?></td>
                            <td class="px-3 py-2 text-amber-600"><?= (int)($p['baixo'] ?? 0) ?></td>
                            <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($p['devolucao'] ?? 0) ?></td>
                            <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($p['vendas_mes'] ?? 0) ?></td>
                            <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($p['transferencias_mes'] ?? 0) ?></td>
                    <td class="px-3 py-2">
                        <div class="flex gap-1 text-xs">
                            <a href="/admin/consignado/parceiro/<?= $id ?>/ver" class="px-3 py-1 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Ver</a>
                            <a href="/admin/consignado/parceiro/<?= $id ?>/ver#transferir" class="px-3 py-1 rounded-lg bg-stone-900 text-white hover:bg-stone-800">Transferir</a>
                            <a href="/admin/consignado/parceiro/<?= $id ?>/ver#devolver" class="px-3 py-1 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Devolver</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
</section>
