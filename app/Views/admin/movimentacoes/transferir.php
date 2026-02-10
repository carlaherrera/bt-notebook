<?php
// /app/Views/admin/movimentacoes/transferir.php
use App\Core\Security;
?>
<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                    Transferir estoque
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Transferir estoque</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Movimente itens entre parceiros ou ajuste manualmente.</p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="/admin/movimentacoes" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm xl:col-span-2">
            <form method="POST" action="/admin/movimentacoes" class="space-y-4">
                <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                <input type="hidden" name="tipo" value="transferencia">

                <div class="grid gap-3 md:grid-cols-2">
                    <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-semibold">Parceiro</span>
                        <select name="parceiro_id" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($parceiros as $p): ?>
                                <option value="<?= (int)$p['id'] ?>" <?= isset($parceiroSelecionado) && $parceiroSelecionado === (int)$p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-semibold">Produto</span>
                        <select name="produto" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($produtos as $prod): ?>
                                <option value="<?= htmlspecialchars($prod['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"><?= htmlspecialchars($prod['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>

                <div class="grid gap-3 md:grid-cols-3">
                    <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-semibold">Quantidade</span>
                        <input type="number" name="quantidade" min="1" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" required>
                    </label>
                    <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-semibold">Data/hora</span>
                        <input type="datetime-local" name="datahora" value="<?= date('Y-m-d\TH:i') ?>" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" required>
                    </label>
                    <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-semibold">NF Ref.</span>
                        <input type="text" name="nf_ref" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Opcional">
                    </label>
                </div>

                <div class="grid gap-3 md:grid-cols-2">
                    <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-semibold">Lote</span>
                        <input type="text" name="lote" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Opcional">
                    </label>
                    <label class="space-y-1 text-sm text-gray-700 dark:text-gray-200">
                        <span class="font-semibold">Observação</span>
                        <input type="text" name="observacao" class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm" placeholder="Detalhes adicionais">
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="/admin/movimentacoes" class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">Cancelar</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[var(--primary-500)] text-white text-sm font-semibold hover:bg-[var(--primary-600)]">
                        <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                        Transferir
                    </button>
                </div>
            </form>
        </div>

        <aside class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm space-y-3">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Dicas</h3>
            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                <li>Use NF Ref. para rastrear o documento de envio.</li>
                <li>Preencha lote para rastreabilidade.</li>
                <li>Quantidades negativas não são permitidas.</li>
            </ul>
        </aside>
    </div>
</section>
