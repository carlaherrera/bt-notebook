<?php
// /app/Views/cliente/pedidos/show.php
use App\Core\Security;
?>
<section class="space-y-6">
    <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-800/60 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-100">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                Pedido #<?= htmlspecialchars($pedido['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
            </p>
            <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Detalhes do pedido</h1>
            <p class="text-sm text-stone-600 dark:text-stone-300">Status: <?= htmlspecialchars($pedido['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="/cliente/pedidos" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar
            </a>
            <a href="/cliente/pedidos/<?= (int)$pedido['id'] ?>/editar" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Editar
            </a>
            <form action="/cliente/pedidos/<?= (int)$pedido['id'] ?>/excluir" method="POST" onsubmit="return confirm('Deseja excluir este pedido?');" class="inline-flex">
                <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                <button class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-300 hover:bg-stone-100 dark:hover:bg-stone-800">
                    <i data-lucide="trash" class="w-4 h-4"></i>
                    Excluir
                </button>
            </form>
            <?php if (($pedido['status'] ?? '') === 'criado'): ?>
                <form action="/cliente/pedidos/<?= (int)$pedido['id'] ?>/cancelar" method="POST" onsubmit="return confirm('Cancelar este pedido?');" class="inline-flex">
                    <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                    <button class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                        Cancelar
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </header>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Itens</h3>
                    <span class="text-xs text-stone-500">Subtotal: R$ <?= number_format((float)($pedido['subtotal'] ?? 0), 2, ',', '.') ?></span>
                </div>
                <div class="divide-y divide-stone-200 dark:divide-stone-800">
                    <?php foreach (($itens ?? []) as $item): ?>
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($item['nome_snapshot'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                                <p class="text-xs text-stone-500">Qtd: <?= (int)($item['qtd'] ?? 0) ?></p>
                            </div>
                            <div class="text-right text-sm text-stone-900 dark:text-white">
                                <p>R$ <?= number_format((float)($item['preco_unitario'] ?? 0), 2, ',', '.') ?></p>
                                <p class="text-xs text-stone-500">Total: R$ <?= number_format((float)($item['total_linha'] ?? 0), 2, ',', '.') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white mb-2">Resumo</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-300">Subtotal</span>
                        <span class="font-semibold text-stone-900 dark:text-white">R$ <?= number_format((float)($pedido['subtotal'] ?? 0), 2, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-600 dark:text-stone-300">Frete</span>
                        <span class="font-semibold text-stone-900 dark:text-white">R$ <?= number_format((float)($pedido['frete'] ?? 0), 2, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between border-t border-stone-200 dark:border-stone-800 pt-2">
                        <span class="text-stone-700 dark:text-stone-200">Total</span>
                        <span class="text-lg font-bold text-stone-900 dark:text-white">R$ <?= number_format((float)($pedido['total'] ?? 0), 2, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white mb-2">Endereço de entrega</h3>
                <p class="text-sm text-stone-700 dark:text-stone-200">
                    <?= htmlspecialchars($pedido['endereco_titulo'] ?? 'Endereço', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                    <?= htmlspecialchars($pedido['linha1'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> <?= htmlspecialchars($pedido['linha2'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                    <?= htmlspecialchars($pedido['cidade'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> - <?= htmlspecialchars($pedido['estado'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>, <?= htmlspecialchars($pedido['cep'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                </p>
            </div>
        </aside>
    </div>
</section>
