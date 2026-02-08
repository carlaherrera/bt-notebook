<?php
// /app/Views/admin/movimentacoes/index.php
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
                    <i data-lucide="shuffle" class="w-4 h-4"></i>
                    Movimentações
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Fluxo de estoque</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Entradas, transferências, vendas, devoluções e ajustes.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <form method="GET" action="/admin/movimentacoes" class="flex flex-wrap gap-2 items-center">
                    <input type="hidden" name="dias" value="<?= (int)($filtros['dias'] ?? 30) ?>" />
                    <input type="text" name="tipo" value="<?= htmlspecialchars($filtros['tipo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" placeholder="tipo" class="w-28 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
                    <input type="text" name="parceiro" value="<?= htmlspecialchars($filtros['parceiro'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" placeholder="parceiro" class="w-32 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
                    <input type="text" name="produto" value="<?= htmlspecialchars($filtros['produto'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" placeholder="produto" class="w-32 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
                    <select name="dias" class="w-28 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                        <?php foreach ([7, 30, 90, 180] as $d): ?>
                            <option value="<?= $d ?>" <?= (int)($filtros['dias'] ?? 30) === $d ? 'selected' : '' ?>>Últimos <?= $d ?>d</option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                        Filtrar
                    </button>
                    <a href="/admin/movimentacoes" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Limpar
                    </a>
                </form>
                <a href="/admin/movimentacoes/nova" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Nova movimentação
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar CSV
                </button>
            </div>
        </div>
    </header>

    <!-- Resumo -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Entradas</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['entradas'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Itens recebidos</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Transferências</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['transferencias'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Para parceiros</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Vendas</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['vendas'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Loja + parceiros</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Devoluções</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['devolucoes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Itens retornados</p>
        </div>
    </div>

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
            <i data-lucide="filter" class="w-4 h-4"></i>
            Tipo: <?= htmlspecialchars($filtros['tipo'] ?? 'Todos', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
    </div>

    <!-- Lista -->
    <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Movimentações recentes</h3>
            <div class="flex gap-2 text-xs">
                <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">PDF</button>
                <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Excel</button>
            </div>
        </div>
        <div class="overflow-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-3 py-2">Tipo</th>
                        <th class="px-3 py-2">Descrição</th>
                        <th class="px-3 py-2">Parceiro</th>
                        <th class="px-3 py-2">Produto</th>
                        <th class="px-3 py-2">Qtd.</th>
                        <th class="px-3 py-2">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <?php foreach ($linhas as $linha): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                            <td class="px-3 py-2 font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($linha['tipo'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($linha['descricao'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($linha['parceiro'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($linha['produto'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($linha['quantidade'] ?? 0) ?></td>
                            <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= htmlspecialchars($linha['data'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-right text-xs">
                                <?php if (($linha['origem'] ?? '') === 'geral' && isset($linha['id'])): ?>
                                    <a href="/admin/movimentacoes/<?= (int)$linha['id'] ?>/editar" class="px-2 py-1 rounded border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Editar</a>
                                    <form action="/admin/movimentacoes/<?= (int)$linha['id'] ?>/excluir" method="POST" class="inline">
                                        <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken(); ?>">
                                        <button type="submit" class="px-2 py-1 rounded border border-gray-200 dark:border-gray-700 text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/40">Excluir</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-400 dark:text-gray-600">Consignado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
