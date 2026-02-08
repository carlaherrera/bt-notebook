<?php
// /app/Views/cliente/enderecos/index.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    Endereços
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Meus endereços</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Gerencie endereços de entrega e cobrança.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar ao painel
                </a>
                <a href="#novo" class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Novo endereço
                </a>
            </div>
        </div>
    </header>

    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Endereços salvos</h3>
                <span class="text-[11px] text-stone-500 dark:text-stone-400">Mock</span>
            </div>
            <div class="grid gap-3 md:grid-cols-2">
                <?php foreach ($enderecos as $end): ?>
                    <div class="p-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($end['titulo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h4>
                                <?php if (!empty($end['principal'])): ?>
                                    <span class="text-[11px] px-2 py-[3px] rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">Principal</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex gap-1 text-xs">
                                <button class="px-2 py-1 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Editar</button>
                                <button class="px-2 py-1 rounded-lg border border-rose-200 dark:border-rose-700 text-rose-700 dark:text-rose-200 hover:bg-rose-50 dark:hover:bg-rose-900/40">Excluir</button>
                            </div>
                        </div>
                        <p class="text-sm text-stone-700 dark:text-stone-200 leading-snug">
                            <?= htmlspecialchars($end['linha1'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?><br>
                            <?= htmlspecialchars($end['linha2'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </p>
                        <p class="text-sm text-stone-600 dark:text-stone-300">
                            <?= htmlspecialchars($end['cidade'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · CEP <?= htmlspecialchars($end['cep'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3" id="novo">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Adicionar endereço</h3>
                <span class="text-[11px] text-stone-500 dark:text-stone-400">Mock</span>
            </div>
            <form class="space-y-3">
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Título</span>
                    <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Casa / Trabalho">
                </label>
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Endereço</span>
                    <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Rua, número, complemento">
                </label>
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Cidade / Estado</span>
                    <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Cidade / UF">
                </label>
                <label class="space-y-1 block text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">CEP</span>
                    <input type="text" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="00000-000">
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 dark:text-stone-200">
                    <input type="checkbox" class="rounded border-stone-300 text-stone-900 dark:bg-stone-900 dark:border-stone-700">
                    Definir como principal
                </label>
                <button type="button" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar endereço
                </button>
            </form>
        </div>
    </div>
</section>
