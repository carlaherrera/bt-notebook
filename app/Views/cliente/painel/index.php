<section class="space-y-6">

    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Cliente
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Seu painel</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Acompanhe pedidos, entregas e suporte em um só lugar.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="#pedidos" class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    Meus pedidos
                </a>
                <a href="#suporte" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="life-buoy" class="w-4 h-4"></i>
                    Suporte
                </a>
            </div>
        </div>
    </header>

    <!-- KPIs -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-stone-500 dark:text-stone-300">Pedidos</p>
            <p class="text-2xl font-bold text-stone-900 dark:text-white mt-1"><?= (int)($pedidosMetrics['total'] ?? 0) ?></p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Em andamento: <?= (int)($pedidosMetrics['em_andamento'] ?? 0) ?></p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-stone-500 dark:text-stone-300">Entregues</p>
            <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1"><?= (int)($pedidosMetrics['entregues'] ?? 0) ?></p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Inclui últimos pedidos</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-stone-500 dark:text-stone-300">Tickets</p>
            <p class="text-2xl font-bold text-amber-700 dark:text-amber-300 mt-1"><?= (int)($ticketMetrics['tickets_abertos'] ?? 0) ?></p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Abertos ou em atendimento</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-stone-500 dark:text-stone-300">Financeiro</p>
            <p class="text-2xl font-bold text-stone-900 dark:text-white mt-1"><?= (int)($faturasMetrics['faturas_pendentes'] ?? 0) ?> pend.</p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Notas: <?= (int)($notasMetrics['notas_total'] ?? 0) ?></p>
        </div>
    </div>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3" id="pedidos">
        <!-- Pedidos -->
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Últimos pedidos</h3>
                <a href="/cliente/pedidos" class="text-xs font-semibold text-stone-700 dark:text-stone-200 hover:underline">Ver todos</a>
            </div>
            <div class="overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-stone-500 dark:text-stone-400">
                        <tr class="bg-stone-50 dark:bg-stone-900/50">
                            <th class="px-3 py-2 text-left">Pedido</th>
                            <th class="px-3 py-2 text-left">Data</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        <?php foreach (($pedidosRecentes ?? []) as $pedido): ?>
                            <?php
                                $dataFmt = !empty($pedido['created_at']) ? date('d/m/Y', strtotime($pedido['created_at'])) : '';
                                $status = $pedido['status'] ?? '—';
                                $statusClass = 'bg-stone-100 text-stone-800 dark:bg-stone-800 dark:text-stone-200';
                                if ($status === 'entregue') $statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-200';
                                if ($status === 'em_rota') $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-200';
                                if ($status === 'pago') $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-200';
                                if ($status === 'cancelado') $statusClass = 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-200';
                            ?>
                            <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/60">
                                <td class="px-3 py-2 font-semibold text-stone-900 dark:text-white">#<?= (int)($pedido['id'] ?? 0) ?></td>
                                <td class="px-3 py-2 text-stone-600 dark:text-stone-300"><?= htmlspecialchars($dataFmt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                <td class="px-3 py-2"><span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold <?= $statusClass ?>"><?= htmlspecialchars(str_replace('_',' ', $status), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span></td>
                                <td class="px-3 py-2 text-right text-stone-900 dark:text-white">R$ <?= number_format((float)($pedido['total'] ?? 0), 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pedidosRecentes)): ?>
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-sm text-stone-600 dark:text-stone-300">Nenhum pedido encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ações rápidas -->
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3" id="suporte">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Ações rápidas</h3>
                <span class="text-[11px] text-stone-500 dark:text-stone-400">Suporte</span>
            </div>
            <div class="space-y-2">
                <a href="/cliente/suporte" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                    <i data-lucide="life-buoy" class="w-4 h-4"></i>
                    Abrir chamado
                </a>
                <a href="/cliente/perfil" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    Atualizar perfil
                </a>
                <a href="/cliente/notas" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Ver notas fiscais
                </a>
            </div>
        </div>
    </div>

</section>
