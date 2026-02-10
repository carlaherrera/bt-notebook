<?php
// /app/Views/cliente/suporte/editar.php
use App\Core\Security;
?>
<section class="space-y-6">
    <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-100">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Editar ticket #<?= htmlspecialchars($ticket['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
            </p>
            <h1 class="text-2xl font-bold text-stone-900 dark:text-white">Ajustar ticket</h1>
            <p class="text-sm text-stone-600 dark:text-stone-300">Atualize status, prioridade e assunto.</p>
        </div>
        <div class="flex gap-2">
            <a href="/cliente/suporte/<?= (int)$ticket['id'] ?>" class="px-4 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Voltar</a>
        </div>
    </header>

    <form action="/cliente/suporte/<?= (int)$ticket['id'] ?>/editar" method="POST" class="grid gap-4 lg:grid-cols-3">
        <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

        <div class="lg:col-span-2 space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Assunto</span>
                    <input type="text" name="assunto" value="<?= htmlspecialchars($ticket['assunto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                </label>
                <div class="grid gap-3 md:grid-cols-3">
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Categoria</span>
                        <select name="categoria" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= htmlspecialchars($cat, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" <?= strtolower($cat) === strtolower($ticket['categoria']) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($cat), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Prioridade</span>
                        <select name="prioridade" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                            <?php foreach ($prioridades as $p): ?>
                                <option value="<?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" <?= strtolower($p) === strtolower($ticket['prioridade']) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($p), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Status</span>
                        <select name="status" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                            <?php foreach ($statusList as $s): ?>
                                <option value="<?= htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" <?= strtolower($s) === strtolower($ticket['status']) ? 'selected' : '' ?>><?= htmlspecialchars(str_replace('_',' ',$s), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
            </div>
        </div>

        <aside class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-4">
            <div>
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Resumo</h3>
                <p class="text-xs text-stone-500">Pedido: <?= $ticket['pedido_id'] ? '#'.(int)$ticket['pedido_id'] : '—' ?></p>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800">
                <i data-lucide="save" class="w-4 h-4"></i>
                Salvar alterações
            </button>
        </aside>
    </form>
</section>
