<?php
// /app/Views/cliente/notas/show.php
?>
<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Nota fiscal
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">NFe <?= htmlspecialchars($nota['numero'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Pedido: #<?= htmlspecialchars($nota['pedido_id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente/notas" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
                <a href="/cliente/notas/<?= (int)$nota['id'] ?>/editar" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    Editar
                </a>
                <a href="<?= htmlspecialchars($nota['link_download'] ?? '#', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800" target="_blank" rel="noreferrer">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Baixar PDF
                </a>
            </div>
        </div>
    </header>

    <div class="grid gap-4 lg:grid-cols-3">
        <?php $dataFmt = !empty($nota['data_emissao']) ? date('d/m/Y', strtotime($nota['data_emissao'])) : ''; ?>

        <div class="lg:col-span-2 space-y-4">
            <div class="p-5 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm grid gap-4 md:grid-cols-2">
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-wide text-stone-500 dark:text-stone-400">Número</p>
                    <p class="text-lg font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($nota['numero'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-wide text-stone-500 dark:text-stone-400">Série</p>
                    <p class="text-lg font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($nota['serie'] ?? '—', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-wide text-stone-500 dark:text-stone-400">Emissão</p>
                    <p class="text-lg font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($dataFmt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-wide text-stone-500 dark:text-stone-400">Valor</p>
                    <p class="text-2xl font-bold text-stone-900 dark:text-white">R$ <?= number_format((float)($nota['valor'] ?? 0), 2, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="p-5 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white mb-2">Download</h3>
                <p class="text-xs text-stone-600 dark:text-stone-300 mb-3">Baixe o PDF da nota fiscal.</p>
                <a href="<?= htmlspecialchars($nota['link_download'] ?? '#', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="inline-flex items-center gap-2 w-full justify-center rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800" target="_blank" rel="noreferrer">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Baixar PDF
                </a>
            </div>
        </aside>
    </div>
</section>
