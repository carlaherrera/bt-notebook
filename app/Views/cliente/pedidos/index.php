<?php
// /app/Views/cliente/pedidos/index.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    Meus pedidos
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Pedidos</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Acompanhe status, entregas e detalhes.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar ao painel
                </a>
                <a href="/cliente/pedidos/novo" class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Novo pedido
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar
                </button>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Lista de pedidos</h3>
                <div class="flex gap-2 text-xs">
                    <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">Todos</button>
                    <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">Em entrega</button>
                    <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">Entregues</button>
                </div>
            </div>

            <div class="space-y-3">
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="p-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-sm">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($pedido['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                    <span class="text-[11px] px-2 py-[3px] rounded-full border border-stone-200 dark:border-stone-700 text-stone-600 dark:text-stone-200">
                                        <?= htmlspecialchars($pedido['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                    </span>
                                </div>
                                <p class="text-xs text-stone-600 dark:text-stone-400">Data: <?= htmlspecialchars($pedido['data'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            </div>
                            <div class="text-right text-sm font-semibold text-stone-900 dark:text-white">
                                <?= htmlspecialchars($pedido['total'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                            </div>
                        </div>
                        <div class="mt-3 text-xs text-stone-600 dark:text-stone-400 space-y-1">
                            <?php foreach ($pedido['itens'] as $item): ?>
                                <div class="flex items-center justify-between">
                                    <span><?= htmlspecialchars($item['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                    <span class="text-stone-500 dark:text-stone-300">Qtd: <?= (int)($item['qtd'] ?? 0) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                Ver detalhes
                            </a>
                            <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                <i data-lucide="truck" class="w-4 h-4"></i>
                                Rastrear entrega
                            </a>
                            <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                <i data-lucide="life-buoy" class="w-4 h-4"></i>
                                Abrir chamado
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Filtros</h3>
                <button class="text-xs text-stone-600 dark:text-stone-300 hover:underline">Limpar</button>
            </div>
            <div class="space-y-3 text-sm text-stone-700 dark:text-stone-200">
                <label class="space-y-1 block">
                    <span class="text-xs font-semibold">Status</span>
                    <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                        <option>Todos</option>
                        <option>Em separação</option>
                        <option>Em rota</option>
                        <option>Entregue</option>
                    </select>
                </label>
                <label class="space-y-1 block">
                    <span class="text-xs font-semibold">Período</span>
                    <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                        <option>Últimos 30 dias</option>
                        <option>Últimos 90 dias</option>
                        <option>2026</option>
                    </select>
                </label>
                <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Aplicar filtros
                </button>
            </div>
        </div>
    </div>
</section>
