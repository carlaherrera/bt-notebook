<?php
use App\Core\Security;
?>

<section class="space-y-6">
    <header class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-[0.18em]">Admin</p>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Editar produto</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Atualize informações de estoque e status.</p>
        </div>
        <a href="/admin/produtos" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] shadow-sm">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Voltar
        </a>
    </header>

    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-6">
        <form action="/admin/produtos/<?= (int)($produto['id'] ?? 0) ?>/editar" method="POST" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="nome">Nome</label>
                    <input id="nome" name="nome" value="<?= htmlspecialchars($produto['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Nome do produto">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="sku">SKU</label>
                    <input id="sku" name="sku" value="<?= htmlspecialchars($produto['sku'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="SKU único">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="categoria">Categoria</label>
                    <input id="categoria" name="categoria" value="<?= htmlspecialchars($produto['categoria'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Ex: Proteína">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="preco">Preço</label>
                    <input id="preco" name="preco" type="number" step="0.01" min="0" value="<?= htmlspecialchars(number_format((float)($produto['preco'] ?? 0), 2, '.', ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="0,00">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="status">Status</label>
                    <select id="status" name="status" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                        <?php $st = strtolower($produto['status'] ?? 'ativo'); ?>
                        <option value="ativo" <?= $st === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                        <option value="alerta" <?= $st === 'alerta' ? 'selected' : '' ?>>Alerta</option>
                        <option value="critico" <?= $st === 'critico' ? 'selected' : '' ?>>Crítico</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="estoque_loja">Estoque loja</label>
                    <input id="estoque_loja" name="estoque_loja" type="number" min="0" value="<?= (int)($produto['estoque_loja'] ?? 0) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="estoque_consignado">Estoque consignado</label>
                    <input id="estoque_consignado" name="estoque_consignado" type="number" min="0" value="<?= (int)($produto['estoque_consignado'] ?? 0) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="minimo">Mínimo</label>
                    <input id="minimo" name="minimo" type="number" min="0" value="<?= (int)($produto['minimo'] ?? 0) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="fabricado_em">Fabricado em</label>
                    <input id="fabricado_em" name="fabricado_em" type="date" value="<?= htmlspecialchars($produto['fabricado_em'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="expira_em">Validade</label>
                    <input id="expira_em" name="expira_em" type="date" value="<?= htmlspecialchars($produto['expira_em'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="lote">Lote</label>
                    <input id="lote" name="lote" maxlength="120" value="<?= htmlspecialchars($produto['lote'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Identificador do lote">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" rows="3" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Cuidados de manuseio, armazenagem, etc."><?= htmlspecialchars($produto['observacoes'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/admin/produtos" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-[var(--primary-600)] transition">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Salvar alterações
                </button>
            </div>
        </form>
    </section>
</section>
