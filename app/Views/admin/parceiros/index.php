<?php
// /app/Views/admin/parceiros/index.php
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
                    <i data-lucide="handshake" class="w-4 h-4"></i>
                    Parceiros
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Parceiros e consignados</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Gestão de academias e personals com estoque consignado.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/parceiros" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                    Atualizar lista
                </a>
                <a href="/admin/parceiros/novo" class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Novo parceiro
                </a>
            </div>
        </div>
    </header>

    <!-- Resumo -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-5">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Parceiros ativos</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['ativos'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Academias e personals</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Inativos</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['inativos'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pausados ou sem contrato</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Itens consignados</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['itens_total'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total em parceiros</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Itens em alerta</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['estoque_baixo'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Abaixo do mínimo</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Vendas no mês</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['vendas_mes'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Origem parceiros</p>
        </div>
    </div>

    <!-- Lista de parceiros -->
    <div class="flex flex-col gap-3">
        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                <i data-lucide="list-filter" class="w-4 h-4"></i>
                Filtros rápidos
            </div>
            <button class="px-3 py-1.5 text-xs rounded-full border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Todos</button>
            <button class="px-3 py-1.5 text-xs rounded-full border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover-bg-gray-800">Academias</button>
        </div>

        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($parceiros as $p): ?>
                <div class="p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm flex flex-col gap-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($p['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                <span class="text-[11px] px-2 py-[3px] rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($p['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <?= htmlspecialchars($p['documento'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars($p['cidade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                            </p>
                            <div class="flex gap-2 text-[11px] text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1"><i data-lucide="user" class="w-3.5 h-3.5"></i><?= htmlspecialchars($p['contato'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                <span class="inline-flex items-center gap-1"><i data-lucide="phone" class="w-3.5 h-3.5"></i><?= htmlspecialchars($p['telefone'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                                <i data-lucide="mail" class="w-3.5 h-3.5 inline"></i> <?= htmlspecialchars($p['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[11px] px-2 py-[3px] rounded-full border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                            <i data-lucide="dot" class="w-3 h-3 <?= strtolower($p['status']) === 'ativo' ? 'text-emerald-500' : 'text-amber-500' ?>"></i>
                            <?= htmlspecialchars($p['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 text-center text-xs">
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60 p-3">
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Itens</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white"><?= (int)($p['itens'] ?? 0) ?></p>
                        </div>
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60 p-3">
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Baixo</p>
                            <p class="text-lg font-bold text-amber-600"><?= (int)($p['baixo'] ?? 0) ?></p>
                        </div>
                        <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60 p-3">
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Devolução</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white"><?= (int)($p['devolucao'] ?? 0) ?></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <div class="inline-flex items-center gap-1">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            Vendas mês: <span class="font-semibold text-gray-800 dark:text-gray-100 ml-1"><?= (int)($p['vendas_mes'] ?? 0) ?></span>
                        </div>
                        <div class="inline-flex items-center gap-1">
                            <i data-lucide="wallet" class="w-4 h-4"></i>
                            Ticket: <span class="font-semibold text-gray-800 dark:text-gray-100 ml-1"><?= htmlspecialchars(number_format((float)($p['ticket_medio'] ?? 0), 2, ',', '.'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1"><i data-lucide="clock-3" class="w-3.5 h-3.5"></i> Atualizado: <?= htmlspecialchars($p['atualizado_em'] ?? $p['updated_at'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                        <div class="flex gap-1">
                            <a href="/admin/parceiros/<?= (int)($p['id'] ?? 0) ?>/ver" class="px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Ver detalhes</a>
                            <button class="px-3 py-1 rounded-lg bg-[var(--primary-500)] text-white hover:bg-[var(--primary-600)]">Transferir</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
