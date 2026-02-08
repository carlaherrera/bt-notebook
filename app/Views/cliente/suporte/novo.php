<?php
// /app/Views/cliente/suporte/novo.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="life-buoy" class="w-4 h-4"></i>
                    Abrir chamado
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Novo ticket</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Envie sua solicitação ao suporte.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente/suporte" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar rascunho
                </button>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
            <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Detalhes do chamado</h3>
            <form class="space-y-3 text-sm text-stone-700 dark:text-stone-200">
                <label class="space-y-1 block">
                    <span class="font-semibold">Assunto</span>
                    <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Ex: Atraso na entrega">
                </label>
                <label class="space-y-1 block">
                    <span class="font-semibold">Categoria</span>
                    <select class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                        <?php foreach ($categorias as $cat): ?>
                            <option><?= htmlspecialchars($cat, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="space-y-1 block">
                    <span class="font-semibold">Descrição</span>
                    <textarea rows="6" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Conte o que aconteceu"></textarea>
                </label>
                <label class="space-y-1 block">
                    <span class="font-semibold">Anexos (opcional)</span>
                    <input type="file" class="w-full text-sm text-stone-700 dark:text-stone-200">
                </label>
                <button type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Enviar chamado (mock)
                </button>
                <p class="text-[11px] text-stone-500 dark:text-stone-400">Mock de layout; integrar com backend depois.</p>
            </form>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Resumo</h3>
                <span class="text-[11px] text-stone-500 dark:text-stone-400">Mock</span>
            </div>
            <ul class="space-y-2 text-sm text-stone-700 dark:text-stone-200">
                <li class="flex justify-between"><span>Tempo médio de resposta</span><span>1h</span></li>
                <li class="flex justify-between"><span>Chamados abertos</span><span>2</span></li>
                <li class="flex justify-between"><span>Prioridade média</span><span>Alta</span></li>
            </ul>
            <div class="p-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-800/40 text-xs text-stone-700 dark:text-stone-200 space-y-1">
                <p class="font-semibold text-stone-900 dark:text-white">Dicas</p>
                <p>Inclua imagens e NF se houver problema de entrega.</p>
                <p>Explique o contexto para acelerar o atendimento.</p>
            </div>
        </div>
    </div>
</section>
