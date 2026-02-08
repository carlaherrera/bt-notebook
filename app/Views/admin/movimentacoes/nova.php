<?php
// /app/Views/admin/movimentacoes/nova.php
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
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Nova movimentação
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Registrar movimentação</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Preencha os campos para simular uma entrada, transferência, venda ou devolução.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/movimentacoes" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <button form="form-mov" type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar
                </button>
            </div>
        </div>
    </header>

    <form id="form-mov" action="/admin/movimentacoes" method="POST" class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken(); ?>" />

        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Tipo</span>
                <select name="tipo" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <option value="" disabled selected>Selecione</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= htmlspecialchars($t, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                            <?= htmlspecialchars(ucfirst($t), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Parceiro (opcional)</span>
                <select name="parceiro_id" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <option value="">Loja</option>
                    <?php foreach ($parceiros as $p): ?>
                        <option value="<?= (int)($p['id'] ?? 0) ?>"><?= htmlspecialchars($p['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Produto</span>
                <select name="produto" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <option value="" disabled selected>Selecione</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"><?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <div class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Quantidade</span>
                <input name="quantidade" type="number" min="1" value="1" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </div>
        </div>

        <div class="grid gap-4 grid-cols-1 md:grid-cols-3">
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">NF / Referência</span>
                <input name="nf_ref" type="text" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </label>
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Lote</span>
                <input name="lote" type="text" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </label>
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Data/hora</span>
                <input name="datahora" type="datetime-local" value="<?= htmlspecialchars(date('Y-m-d\TH:i'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </label>
        </div>

        <div class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
            <span class="font-semibold">Observações</span>
            <textarea name="observacao" rows="3" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Ex: Envio semanal para parceiro, incluir amostras."></textarea>
        </div>

        <div class="flex flex-wrap gap-2 text-xs text-gray-600 dark:text-gray-300">
            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                Protegido por CSRF
            </span>
        </div>
    </form>
</section>
