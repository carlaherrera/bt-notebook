<?php
// /app/Views/cliente/suporte/index.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="life-buoy" class="w-4 h-4"></i>
                    Suporte
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Central de suporte</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Acompanhe chamados e abra novas solicitações.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar ao painel
                </a>
                <a href="#novo" class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Abrir chamado
                </a>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Meus tickets</h3>
                <div class="flex gap-2 text-xs">
                    <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">Todos</button>
                    <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">Abertos</button>
                    <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">Resolvidos</button>
                </div>
            </div>

            <div class="space-y-3">
                <?php foreach ($tickets as $ticket): ?>
                    <?php
                        $status = strtolower($ticket['status']);
                        $statusClass = $status === 'resolvido'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200'
                            : ($status === 'em atendimento'
                                ? 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-200'
                                : 'bg-stone-200 text-stone-800 dark:bg-stone-700 dark:text-stone-100');
                    ?>
                    <div class="p-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-sm">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($ticket['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                                    <span class="text-[11px] px-2 py-[3px] rounded-full <?= $statusClass ?>">
                                        <?= htmlspecialchars($ticket['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                    </span>
                                </div>
                                <p class="text-sm text-stone-700 dark:text-stone-200 font-semibold"><?= htmlspecialchars($ticket['assunto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                                <p class="text-xs text-stone-600 dark:text-stone-400">Atualizado: <?= htmlspecialchars($ticket['atualizado'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            </div>
                            <div class="text-right text-xs text-stone-500 dark:text-stone-400">
                                Prioridade: <?= htmlspecialchars($ticket['prioridade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                            </div>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs">
                            <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                <i data-lucide="message-square" class="w-4 h-4"></i>
                                Ver conversa
                            </a>
                            <a href="#" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                <i data-lucide="paperclip" class="w-4 h-4"></i>
                                Enviar anexo
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3" id="novo">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Abrir chamado</h3>
                <span class="text-[11px] text-stone-500 dark:text-stone-400">Resposta média: 1h</span>
            </div>
            <form class="space-y-3">
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Assunto</span>
                    <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Ex: Atraso na entrega">
                </label>
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Categoria</span>
                    <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                        <option>Entrega</option>
                        <option>Pagamento</option>
                        <option>Troca/Devolução</option>
                        <option>Outros</option>
                    </select>
                </label>
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Descrição</span>
                    <textarea rows="4" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Detalhe o que aconteceu"></textarea>
                </label>
                <button type="button" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Enviar chamado
                </button>
                <p class="text-xs text-stone-500 dark:text-stone-400">Dados mockados para layout; integrar API quando disponível.</p>
            </form>
        </div>
    </div>
</section>
