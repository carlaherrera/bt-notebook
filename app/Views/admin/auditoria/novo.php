<?php
use App\Core\Security;
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                    Nova auditoria
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Criar auditoria</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Defina status inicial e descrição breve.</p>
            </div>
            <a href="/admin/auditoria" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar
            </a>
        </div>
    </header>

    <form action="/admin/auditoria" method="POST" class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
        <input type="hidden" name="_csrf" value="<?= Security::csrfToken(); ?>">

        <div class="grid gap-4 md:grid-cols-2">
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Status</span>
                <select name="status" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <option value="pendente">Pendente</option>
                    <option value="em_andamento">Em andamento</option>
                    <option value="concluida">Concluída</option>
                </select>
            </label>
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Descrição</span>
                <input name="descricao" type="text" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Ex: Contagem loja principal">
            </label>
        </div>

        <div class="rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-4 space-y-3 bg-gray-50/50 dark:bg-gray-800/40">
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Item inicial (opcional)</p>
            <div class="grid gap-4 md:grid-cols-2">
                <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                    <span class="font-semibold">Produto</span>
                    <select name="item_produto" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                        <option value="">Selecione</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= htmlspecialchars($p['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"><?= htmlspecialchars($p['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                    <span class="font-semibold">Parceiro</span>
                    <select name="item_local" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                        <option value="">Loja</option>
                        <?php foreach ($parceiros as $p): ?>
                            <option value="<?= htmlspecialchars($p['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"><?= htmlspecialchars($p['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                    <span class="font-semibold">Qtde sistema</span>
                    <input name="item_qtde_sistema" type="number" min="0" value="0" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                </label>
                <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                    <span class="font-semibold">Qtde física</span>
                    <input name="item_qtde_fisica" type="number" min="0" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Opcional">
                </label>
                <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                    <span class="font-semibold">Status do item</span>
                    <select name="item_status" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                        <option value="pendente">Pendente</option>
                        <option value="conferido">Conferido</option>
                        <option value="divergencia">Divergência</option>
                    </select>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <a href="/admin/auditoria" class="px-4 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Cancelar</a>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold border border-stone-700 shadow-sm hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-800 focus:ring-offset-white dark:focus:ring-offset-stone-900">
                <i data-lucide="save" class="w-4 h-4"></i>
                Salvar auditoria
            </button>
        </div>
    </form>
</section>
