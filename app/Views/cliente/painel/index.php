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
            <p class="text-2xl font-bold text-stone-900 dark:text-white mt-1">12</p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Aguardando entrega</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-stone-500 dark:text-stone-300">Entregues</p>
            <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">5</p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Últimos 30 dias</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-stone-500 dark:text-stone-300">Tickets</p>
            <p class="text-2xl font-bold text-amber-700 dark:text-amber-300 mt-1">2</p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Em suporte</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-stone-500 dark:text-stone-300">Pontos</p>
            <p class="text-2xl font-bold text-stone-900 dark:text-white mt-1">1.250</p>
            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1">Rewards disponíveis</p>
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
                        <tr>
                            <th class="px-3 py-2">Pedido</th>
                            <th class="px-3 py-2">Data</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/60">
                            <td class="px-3 py-2 font-semibold text-stone-900 dark:text-white">#1023</td>
                            <td class="px-3 py-2 text-stone-600 dark:text-stone-300">08/02/2026</td>
                            <td class="px-3 py-2 text-amber-700 dark:text-amber-300">Em separação</td>
                            <td class="px-3 py-2 text-stone-900 dark:text-white">R$ 249,90</td>
                        </tr>
                        <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/60">
                            <td class="px-3 py-2 font-semibold text-stone-900 dark:text-white">#1019</td>
                            <td class="px-3 py-2 text-stone-600 dark:text-stone-300">05/02/2026</td>
                            <td class="px-3 py-2 text-emerald-700 dark:text-emerald-300">Entregue</td>
                            <td class="px-3 py-2 text-stone-900 dark:text-white">R$ 189,00</td>
                        </tr>
                        <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/60">
                            <td class="px-3 py-2 font-semibold text-stone-900 dark:text-white">#1014</td>
                            <td class="px-3 py-2 text-stone-600 dark:text-stone-300">29/01/2026</td>
                            <td class="px-3 py-2 text-amber-700 dark:text-amber-300">Em rota</td>
                            <td class="px-3 py-2 text-stone-900 dark:text-white">R$ 320,00</td>
                        </tr>
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
