<?php
// /app/Views/cliente/notas/editar.php
?>
<section class="space-y-6">
    <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-100">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Editar NFe <?= htmlspecialchars($nota['numero'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
            </p>
            <h1 class="text-2xl font-bold text-stone-900 dark:text-white">Atualizar nota fiscal</h1>
            <p class="text-sm text-stone-600 dark:text-stone-300">Pedido: #<?= htmlspecialchars($nota['pedido_id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        </div>
        <div class="flex gap-2">
            <a href="/cliente/notas/<?= (int)$nota['id'] ?>" class="px-4 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Voltar</a>
        </div>
    </header>

    <form action="/cliente/notas/<?= (int)$nota['id'] ?>/editar" method="POST" class="grid gap-6 lg:grid-cols-3">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken() ?>">
        <div class="lg:col-span-2 space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Número</span>
                    <input type="text" name="numero" value="<?= htmlspecialchars($nota['numero'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                </label>
                <div class="grid gap-3 md:grid-cols-3">
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Série</span>
                        <input type="text" name="serie" value="<?= htmlspecialchars($nota['serie'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                    </label>
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Data de emissão</span>
                        <input type="date" name="data_emissao" value="<?= htmlspecialchars($nota['data_emissao'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                    </label>
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Valor</span>
                        <input type="number" step="0.01" name="valor" value="<?= htmlspecialchars($nota['valor'] ?? '0', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                    </label>
                </div>
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Link para download</span>
                    <input type="url" name="link_download" value="<?= htmlspecialchars($nota['link_download'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="https://...">
                </label>
            </div>
        </div>

        <aside class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-4">
            <div>
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Resumo</h3>
                <p class="text-xs text-stone-500">Pedido: #<?= htmlspecialchars($nota['pedido_id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800">
                <i data-lucide="save" class="w-4 h-4"></i>
                Salvar
            </button>
        </aside>
    </form>
</section>
