<?php
// /app/Views/cliente/pedidos/novo.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    Novo pedido
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Montar pedido</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Escolha itens, endereço e pagamento.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente/pedidos" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar rascunho
                </button>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="xl:col-span-2 space-y-4">
            <!-- Produtos -->
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Itens do pedido</h3>
                    <button class="text-xs text-stone-600 dark:text-stone-300 hover:underline">Limpar</button>
                </div>
                <div class="space-y-3">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="p-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($produto['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                                <p class="text-xs text-stone-600 dark:text-stone-300"><?= htmlspecialchars($produto['preco'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <button class="px-2 py-1 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">-</button>
                                <input type="number" value="1" min="0" class="w-14 text-center rounded-lg border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 py-1 text-sm">
                                <button class="px-2 py-1 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">+</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Endereço e entrega -->
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Entrega</h3>
                <div class="grid gap-3 md:grid-cols-2">
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Endereço</span>
                        <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                            <?php foreach ($enderecos as $end): ?>
                                <option value="<?= (int)($end['id'] ?? 0) ?>"><?= htmlspecialchars($end['label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Modalidade</span>
                        <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                            <option>Entrega padrão</option>
                            <option>Entrega expressa</option>
                            <option>Retirada na loja</option>
                        </select>
                    </label>
                </div>
                <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Observações</span>
                    <textarea rows="3" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Ex: deixar na portaria"></textarea>
                </label>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Pagamento -->
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Pagamento</h3>
                <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200 block">
                    <span class="font-semibold">Método</span>
                    <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                        <?php foreach ($pagamentos as $pay): ?>
                            <option value="<?= (int)($pay['id'] ?? 0) ?>"><?= htmlspecialchars($pay['label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="p-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-800/40 text-sm text-stone-700 dark:text-stone-200">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span>R$ 0,00</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Entrega</span>
                        <span>R$ 0,00</span>
                    </div>
                    <div class="flex items-center justify-between font-semibold text-stone-900 dark:text-white mt-2">
                        <span>Total</span>
                        <span>R$ 0,00</span>
                    </div>
                </div>
                <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Confirmar pedido (mock)
                </button>
                <p class="text-[11px] text-stone-500 dark:text-stone-400">Layout mock. Integre aos dados reais depois.</p>
            </div>

            <!-- Resumo rápido -->
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-2">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Resumo</h3>
                <p class="text-xs text-stone-600 dark:text-stone-300">Selecione itens para atualizar os valores.</p>
                <div class="text-xs text-stone-600 dark:text-stone-300 space-y-1">
                    <div class="flex justify-between"><span>Itens</span><span>0</span></div>
                    <div class="flex justify-between"><span>Entrega</span><span>R$ 0,00</span></div>
                    <div class="flex justify-between font-semibold text-stone-900 dark:text-white"><span>Total</span><span>R$ 0,00</span></div>
                </div>
            </div>
        </div>
    </div>
</section>
