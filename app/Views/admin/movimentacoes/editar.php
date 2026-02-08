<?php
use App\Core\Security;
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Editar movimentação
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Atualizar lançamento</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Ajuste dados de tipo, parceiro, produto e quantidades.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/movimentacoes" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <button form="form-mov-edit" type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar
                </button>
            </div>
        </div>
    </header>

    <form id="form-mov-edit" action="/admin/movimentacoes/<?= (int)($mov['id'] ?? 0) ?>/editar" method="POST" class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
        <input type="hidden" name="_csrf" value="<?= Security::csrfToken(); ?>" />

        <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Tipo</span>
                <select name="tipo" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <?php $tipoSel = strtolower($mov['tipo'] ?? ''); ?>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= htmlspecialchars($t, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" <?= $tipoSel === strtolower($t) ? 'selected' : '' ?>>
                            <?= htmlspecialchars(ucfirst($t), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Parceiro (opcional)</span>
                <select name="parceiro_id" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <option value="">Loja</option>
                    <?php $pid = $mov['parceiro_id'] ?? null; ?>
                    <?php foreach ($parceiros as $p): ?>
                        <option value="<?= (int)($p['id'] ?? 0) ?>" <?= $pid && (int)$pid === (int)($p['id'] ?? 0) ? 'selected' : '' ?>><?= htmlspecialchars($p['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Produto</span>
                <select name="produto" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" <?= ($mov['produto'] ?? '') === $p ? 'selected' : '' ?>><?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <div class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Quantidade</span>
                <input name="quantidade" type="number" min="1" value="<?= (int)($mov['quantidade'] ?? 1) ?>" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </div>
        </div>

        <div class="grid gap-4 grid-cols-1 md:grid-cols-3">
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">NF / Referência</span>
                <input name="nf_ref" type="text" value="<?= htmlspecialchars($mov['nf_ref'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </label>
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Lote</span>
                <input name="lote" type="text" value="<?= htmlspecialchars($mov['lote'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </label>
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Data/hora</span>
                <?php $dt = htmlspecialchars(str_replace(' ', 'T', substr((string)($mov['datahora'] ?? ''), 0, 16)), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                <input name="datahora" type="datetime-local" value="<?= $dt ?>" required class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" />
            </label>
        </div>

        <div class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
            <span class="font-semibold">Observações</span>
            <textarea name="observacao" rows="3" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Notas adicionais"><?= htmlspecialchars($mov['observacao'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
        </div>
    </form>
</section>
