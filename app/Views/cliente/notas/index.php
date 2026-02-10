<?php
// /app/Views/cliente/notas/index.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Notas fiscais
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Notas e recibos</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Baixe suas NFes e comprovantes.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar ao painel
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar tudo
                </button>
            </div>
        </div>
    </header>

    <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-stone-900 dark:text-white">NFes</h3>
            <div class="flex gap-2 text-xs">
                <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">Últimos 30 dias</button>
                <button class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800">2026</button>
            </div>
        </div>
        <div class="overflow-auto">
            <table class="min-w-full text-sm">
                <thead class="text-xs uppercase text-stone-500 dark:text-stone-400">
                    <tr>
                        <th class="px-3 py-2">NFe</th>
                        <th class="px-3 py-2">Pedido</th>
                        <th class="px-3 py-2">Data</th>
                        <th class="px-3 py-2">Valor</th>
                        <th class="px-3 py-2 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                    <?php if (empty($notas)): ?>
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-sm text-stone-600 dark:text-stone-300">Nenhuma nota fiscal encontrada.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($notas as $nota): ?>
                        <?php $dataFmt = !empty($nota['data_emissao']) ? date('d/m/Y', strtotime($nota['data_emissao'])) : ''; ?>
                        <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/60">
                            <td class="px-3 py-2 font-semibold text-stone-900 dark:text-white">NFe <?= htmlspecialchars($nota['numero'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-stone-700 dark:text-stone-200">#<?= htmlspecialchars($nota['pedido_id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-stone-600 dark:text-stone-300"><?= htmlspecialchars($dataFmt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td class="px-3 py-2 text-stone-900 dark:text-white">R$ <?= number_format((float)($nota['valor'] ?? 0), 2, ',', '.') ?></td>
                            <td class="px-3 py-2 text-right text-xs space-x-2">
                                <a href="/cliente/notas/<?= (int)$nota['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    Ver
                                </a>
                                <a href="/cliente/notas/<?= (int)$nota['id'] ?>/editar" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                    Editar
                                </a>
                                <a href="<?= htmlspecialchars($nota['link_download'] ?? '#', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                    Baixar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
