<?php
// /app/Views/cliente/suporte/show.php
use App\Core\Security;
?>
<section class="space-y-6">
    <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-100">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                Ticket #<?= htmlspecialchars($ticket['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
            </p>
            <h1 class="text-2xl font-bold text-stone-900 dark:text-white">Conversa do suporte</h1>
            <p class="text-sm text-stone-600 dark:text-stone-300">Status: <?= htmlspecialchars($ticket['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · Prioridade: <?= htmlspecialchars($ticket['prioridade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="/cliente/suporte" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar
            </a>
            <a href="/cliente/suporte/<?= (int)$ticket['id'] ?>/editar" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Editar
            </a>
        </div>
    </header>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white mb-3">Mensagens</h3>
                <div class="space-y-3">
                    <?php if (empty($mensagens)): ?>
                        <p class="text-sm text-stone-600 dark:text-stone-300">Nenhuma mensagem ainda.</p>
                    <?php endif; ?>
                    <?php foreach ($mensagens as $msg): ?>
                        <?php $dt = !empty($msg['created_at']) ? date('d/m/Y H:i', strtotime($msg['created_at'])) : ''; ?>
                        <div class="p-3 rounded-xl border border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-stone-900/50">
                            <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                                <span><?= ($msg['autor_id'] ?? null) ? 'Você' : 'Atendimento' ?></span>
                                <span><?= htmlspecialchars($dt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                            </div>
                            <p class="mt-1 text-sm text-stone-800 dark:text-stone-100"><?= nl2br(htmlspecialchars($msg['mensagem'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white mb-2">Responder</h3>
                <form method="POST" action="/cliente/suporte/<?= (int)$ticket['id'] ?>/responder" class="space-y-3">
                    <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                    <textarea name="mensagem" rows="3" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Digite sua mensagem" required></textarea>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                            <i data-lucide="send" class="w-4 h-4"></i> Enviar
                        </button>
                        <?php if ($ticket['status'] !== 'fechado'): ?>
                        <form method="POST" action="/cliente/suporte/<?= (int)$ticket['id'] ?>/fechar" onsubmit="return confirm('Fechar este ticket?');">
                            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 text-sm font-semibold" type="submit">
                                <i data-lucide="x-circle" class="w-4 h-4"></i> Fechar
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Detalhes</h3>
                <p class="text-sm text-stone-700 dark:text-stone-200 mt-2">
                    Assunto: <?= htmlspecialchars($ticket['assunto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                    Categoria: <?= htmlspecialchars($ticket['categoria'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                    Prioridade: <?= htmlspecialchars($ticket['prioridade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                    Pedido: <?= $ticket['pedido_id'] ? '#'.(int)$ticket['pedido_id'] : '—' ?>
                </p>
            </div>
        </aside>
    </div>
</section>
