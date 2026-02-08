<?php
// /app/Views/admin/produtos/show.php
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
                    <i data-lucide="package" class="w-4 h-4"></i>
                    Produto
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    <?= htmlspecialchars($produto['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                    <span class="text-[11px] px-2 py-[3px] rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                        <?= htmlspecialchars($produto['categoria'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                    </span>
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">SKU <?= htmlspecialchars($produto['sku'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars($produto['preco'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                <div class="flex flex-wrap gap-3 text-xs text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center gap-1"><i data-lucide="box" class="w-4 h-4"></i> Estoque loja: <?= (int)($produto['estoque_loja'] ?? 0) ?></span>
                    <span class="inline-flex items-center gap-1"><i data-lucide="truck" class="w-4 h-4"></i> Consignado: <?= (int)($produto['estoque_consignado'] ?? 0) ?></span>
                    <span class="inline-flex items-center gap-1"><i data-lucide="alert-triangle" class="w-4 h-4"></i> Mínimo: <?= (int)($produto['minimo'] ?? 0) ?></span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/produtos" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <a href="/admin/produtos/<?= (int)($produto['id'] ?? 0) ?>/editar" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    Editar
                </a>
                <form action="/admin/produtos/<?= (int)($produto['id'] ?? 0) ?>/toggle" method="POST" class="inline">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken(); ?>">
                    <?php $ativo = strtolower($produto['status'] ?? 'ativo') !== 'inativo'; ?>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold <?= $ativo ? 'text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/40' : 'text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/40' ?> transition-colors">
                        <i data-lucide="power" class="w-4 h-4"></i>
                        <?= $ativo ? 'Desativar' : 'Ativar' ?>
                    </button>
                </form>
                <form action="/admin/produtos/<?= (int)($produto['id'] ?? 0) ?>/excluir" method="POST" class="inline" onsubmit="return confirm('Excluir produto?');">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken(); ?>">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/40 transition-colors">
                        <i data-lucide="trash" class="w-4 h-4"></i>
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Resumo rápido -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Estoque total</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($analytics['estoque_total'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Loja + consignado</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Vendido no mês</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($analytics['vendido_mes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Unidades</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Ticket médio</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= htmlspecialchars($analytics['ticket_medio'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Estimado</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Status</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= htmlspecialchars($analytics['status'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Situação atual</p>
        </div>
    </div>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <!-- Parceiros top -->
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Parceiros que mais vendem</h3>
                <span class="text-[11px] text-gray-500 dark:text-gray-400">Últimos 30 dias</span>
            </div>
            <div class="space-y-2">
                <?php foreach ($parceirosTop ?? [] as $p): ?>
                    <div class="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/60 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($p['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400"><?= htmlspecialchars($p['cidade'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        </div>
                        <div class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                            <?= (int)($p['vendido'] ?? 0) ?>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400">unid.</div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($parceirosTop ?? [])): ?>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sem vendas registradas.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Movimentações -->
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Movimentações</h3>
                <button class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs">Exportar</button>
            </div>
            <div class="space-y-2 max-h-72 overflow-auto">
                <?php foreach ($movimentacoes ?? [] as $mov): ?>
                    <div class="p-3 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/60 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($mov['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($mov['descricao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Local: <?= htmlspecialchars($mov['local'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        </div>
                        <div class="text-right text-sm font-semibold text-gray-900 dark:text-white">
                            <?= (int)($mov['quantidade'] ?? 0) ?>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400"><?= htmlspecialchars($mov['data'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($movimentacoes ?? [])): ?>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sem movimentações registradas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
