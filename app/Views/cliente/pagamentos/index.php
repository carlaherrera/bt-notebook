<?php
// /app/Views/cliente/pagamentos/index.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    Pagamentos
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Métodos e faturas</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Gerencie formas de pagamento e baixe suas faturas.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar ao painel
                </a>
                <a href="#novo" class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Adicionar método
                </a>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Formas de pagamento</h3>
                <span class="text-[11px] text-stone-500 dark:text-stone-400">Mock</span>
            </div>
            <div class="space-y-3">
                <?php foreach ($metodos as $metodo): ?>
                    <div class="p-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-sm flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($metodo['apelido'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                <?php if (!empty($metodo['principal'])): ?>
                                    <span class="text-[11px] px-2 py-[3px] rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">Principal</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-xs text-stone-500 dark:text-stone-400 flex items-center gap-2">
                                <?= htmlspecialchars($metodo['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                <?php if (!empty($metodo['validade'])): ?>
                                    <span>· Val.: <?= htmlspecialchars($metodo['validade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex gap-2 text-xs">
                            <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Definir principal</button>
                            <button class="px-3 py-1.5 rounded-lg border border-rose-200 dark:border-rose-700 text-rose-700 dark:text-rose-200 hover:bg-rose-50 dark:hover:bg-rose-900/40">Remover</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="p-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-800/40 space-y-3" id="novo">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Adicionar pagamento</h3>
                    <span class="text-[11px] text-stone-500 dark:text-stone-400">Mock</span>
                </div>
                <form class="space-y-3 text-sm text-stone-700 dark:text-stone-200">
                    <label class="space-y-1 block">
                        <span class="font-semibold">Tipo</span>
                        <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                            <option>Cartão</option>
                            <option>PIX</option>
                            <option>Boleto</option>
                        </select>
                    </label>
                    <label class="space-y-1 block">
                        <span class="font-semibold">Apelido</span>
                        <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Ex: Visa pessoal">
                    </label>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label class="space-y-1 block">
                            <span class="font-semibold">Número</span>
                            <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="•••• •••• •••• ••••">
                        </label>
                        <label class="space-y-1 block">
                            <span class="font-semibold">Validade</span>
                            <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="MM/AA">
                        </label>
                    </div>
                    <div class="grid gap-3 md:grid-cols-2">
                        <label class="space-y-1 block">
                            <span class="font-semibold">Nome no cartão</span>
                            <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Nome completo">
                        </label>
                        <label class="space-y-1 block">
                            <span class="font-semibold">CPF/CNPJ</span>
                            <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="000.000.000-00">
                        </label>
                    </div>
                    <button type="button" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Salvar método
                    </button>
                </form>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Faturas recentes</h3>
                <span class="text-[11px] text-stone-500 dark:text-stone-400">Mock</span>
            </div>
            <div class="space-y-2 text-sm">
                <?php foreach ($faturas as $fat): ?>
                    <div class="p-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($fat['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-xs text-stone-600 dark:text-stone-300">Pedido <?= htmlspecialchars($fat['pedido'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-[11px] text-stone-500 dark:text-stone-400"><?= htmlspecialchars($fat['data'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($fat['valor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-[11px] text-emerald-700 dark:text-emerald-300"><?= htmlspecialchars($fat['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <a href="#" class="text-[11px] text-stone-700 dark:text-stone-200 hover:underline">Baixar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
