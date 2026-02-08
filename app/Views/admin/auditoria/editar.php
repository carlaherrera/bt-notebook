<?php
use App\Core\Security;
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <i data-lucide="clipboard" class="w-4 h-4"></i>
                    Editar auditoria #<?= (int)($auditoria['id'] ?? 0) ?>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Atualizar auditoria</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Ajuste status e descrição.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/auditoria" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <form action="/admin/auditoria/<?= (int)($auditoria['id'] ?? 0) ?>/excluir" method="POST" onsubmit="return confirm('Excluir auditoria?');">
                    <input type="hidden" name="_csrf" value="<?= Security::csrfToken(); ?>">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/40 transition-colors">
                        <i data-lucide="trash" class="w-4 h-4"></i>
                        Excluir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <form action="/admin/auditoria/<?= (int)($auditoria['id'] ?? 0) ?>/editar" method="POST" class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
        <input type="hidden" name="_csrf" value="<?= Security::csrfToken(); ?>">

        <div class="grid gap-4 md:grid-cols-2">
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Status</span>
                <select name="status" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm">
                    <?php $st = strtolower($auditoria['status'] ?? 'pendente'); ?>
                    <option value="pendente" <?= $st === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="em_andamento" <?= $st === 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="concluida" <?= $st === 'concluida' ? 'selected' : '' ?>>Concluída</option>
                </select>
            </label>
            <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                <span class="font-semibold">Descrição</span>
                <input name="descricao" type="text" value="<?= htmlspecialchars($auditoria['descricao'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Ex: Contagem loja principal">
            </label>
        </div>

        <div class="rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-4 space-y-3 bg-gray-50/50 dark:bg-gray-800/40">
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">Adicionar item (opcional)</p>
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

        <?php if (!empty($itens)): ?>
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-900 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Itens da auditoria</h3>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400">Total: <?= count($itens) ?></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400 uppercase text-[11px] tracking-wide">
                                <th class="px-3 py-2">Produto</th>
                                <th class="px-3 py-2">Local</th>
                                <th class="px-3 py-2">Sys</th>
                                <th class="px-3 py-2">Fís</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Criado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <?php foreach ($itens as $it): ?>
                                <?php $st = strtolower($it['status'] ?? 'pendente');
                                $badge = $st === 'divergencia' ? 'text-amber-700 bg-amber-50 border-amber-200 dark:text-amber-200 dark:bg-amber-900/40 dark:border-amber-800' : ($st === 'conferido' ? 'text-emerald-700 bg-emerald-50 border-emerald-200 dark:text-emerald-200 dark:bg-emerald-900/40 dark:border-emerald-800' : 'text-gray-700 bg-gray-50 border-gray-200 dark:text-gray-200 dark:bg-gray-800/60 dark:border-gray-700'); ?>
                                <tr>
                                    <td class="px-3 py-2 text-gray-900 dark:text-white font-semibold"><?= htmlspecialchars($it['produto'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td class="px-3 py-2 text-gray-700 dark:text-gray-200"><?= htmlspecialchars($it['local'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-white"><?= (int)($it['qtde_sistema'] ?? 0) ?></td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-white"><?= $it['qtde_fisica'] === null ? '—' : (int)$it['qtde_fisica'] ?></td>
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-[11px] <?= $badge ?>">
                                            <i data-lucide="dot" class="w-3 h-3"></i>
                                            <?= htmlspecialchars($it['status'] ?? 'pendente', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($it['created_at'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex justify-end gap-2">
            <a href="/admin/auditoria" class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Cancelar</a>
            <button type="submit" class="px-4 py-2 rounded-xl bg-[var(--primary-500)] text-white text-sm font-semibold hover:bg-[var(--primary-600)]">Salvar alterações</button>
        </div>
    </form>
</section>
