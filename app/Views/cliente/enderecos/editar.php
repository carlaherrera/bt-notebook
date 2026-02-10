<?php
// /app/Views/cliente/enderecos/editar.php
use App\Core\Security;
?>
<section class="space-y-6">
    <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-100">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                Editar endereço
            </p>
            <h1 class="text-2xl font-bold text-stone-900 dark:text-white">Atualizar endereço</h1>
            <p class="text-sm text-stone-600 dark:text-stone-300">Ajuste os dados de entrega.</p>
        </div>
        <div class="flex gap-2">
            <a href="/cliente/enderecos" class="px-4 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Voltar</a>
        </div>
    </header>

    <form action="/cliente/enderecos/<?= (int)$endereco['id'] ?>/editar" method="POST" class="grid gap-6 lg:grid-cols-3">
        <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

        <div class="lg:col-span-2 space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Título</span>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($endereco['titulo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                </label>
                <div class="space-y-2">
                    <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Linha 1</span>
                        <input type="text" name="linha1" value="<?= htmlspecialchars($endereco['linha1'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                    </label>
                    <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Linha 2</span>
                        <input type="text" name="linha2" value="<?= htmlspecialchars($endereco['linha2'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                    </label>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Cidade</span>
                        <input type="text" name="cidade" value="<?= htmlspecialchars($endereco['cidade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                    </label>
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Estado</span>
                        <input type="text" name="estado" value="<?= htmlspecialchars($endereco['estado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                    </label>
                </div>
                <div class="grid gap-3 md:grid-cols-3">
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">CEP</span>
                        <input type="text" name="cep" value="<?= htmlspecialchars($endereco['cep'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                    </label>
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200 flex items-center gap-2 mt-5">
                        <input type="checkbox" name="principal" value="1" class="rounded" <?= !empty($endereco['principal']) ? 'checked' : '' ?>>
                        <span class="text-sm">Marcar como principal</span>
                    </label>
                </div>
            </div>
        </div>

        <aside class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-4">
            <div>
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Resumo</h3>
                <p class="text-xs text-stone-500">ID: #<?= (int)$endereco['id'] ?> · Principal: <?= !empty($endereco['principal']) ? 'Sim' : 'Não' ?></p>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800">
                <i data-lucide="save" class="w-4 h-4"></i>
                Salvar alterações
            </button>
        </aside>
    </form>
</section>
