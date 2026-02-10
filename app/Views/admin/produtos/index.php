<?php
// /app/Views/admin/produtos/index.php
?>

<style>
    :root {
        --primary-50: color-mix(in srgb, var(--primary-color) 12%, white);
        --primary-100: color-mix(in srgb, var(--primary-color) 20%, white);
        --primary-200: color-mix(in srgb, var(--primary-color) 32%, white);
        --primary-500: var(--primary-color);
        --primary-600: color-mix(in srgb, var(--primary-color) 85%, black 15%);
        --primary-700: color-mix(in srgb, var(--primary-color) 85%, black 15%);
    }
</style>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    Produtos
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Catálogo</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Admin / Colaborador / Cliente</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">Visualize estoque, consignado e níveis mínimos.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/admin/produtos" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                    Atualizar lista
                </a>
                <a href="/admin/produtos/novo" class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Novo produto
                </a>
            </div>
        </div>
    </header>

    <!-- Resumo -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Ativos</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['ativos'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Produtos disponíveis</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Críticos/alerta</p>
            <p class="text-2xl font-bold text-amber-600 mt-1"><?= (int)($resumo['criticos'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Abaixo do mínimo</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Estoque loja</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['itens_loja'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Itens no depósito</p>
        </div>
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Estoque consignado</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($resumo['itens_consignado'] ?? 0) ?></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Distribuído aos parceiros</p>
        </div>
    </div>

    <!-- Resumo validade -->
    <div class="grid gap-3 grid-cols-1 sm:grid-cols-3 text-xs">
        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-700">
            <p class="font-semibold text-gray-700 dark:text-gray-200">Válidos</p>
            <p class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1"><?= (int)($resumo['val_validos'] ?? 0) ?></p>
            <p class="text-gray-600 dark:text-gray-400">Dentro da validade</p>
        </div>
        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-700">
            <p class="font-semibold text-gray-700 dark:text-gray-200">Próximo</p>
            <p class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1"><?= (int)($resumo['val_proximos'] ?? 0) ?></p>
            <p class="text-gray-600 dark:text-gray-400">Vence em até 30 dias</p>
        </div>
        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-700">
            <p class="font-semibold text-gray-700 dark:text-gray-200">Vencidos</p>
            <p class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1"><?= (int)($resumo['val_vencidos'] ?? 0) ?></p>
            <p class="text-gray-600 dark:text-gray-400">Bloquear vendas</p>
        </div>
    </div>

    <!-- Filtros rápidos -->
    <div class="flex flex-wrap items-center gap-2">
        <div class="inline-flex items-center gap-2 rounded-full border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/70 px-3 py-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
            <i data-lucide="list-filter" class="w-4 h-4"></i>
            Filtros rápidos
        </div>
        <?php $statusVal = $resumo['status_val'] ?? ''; ?>
        <a href="/admin/produtos" class="px-3 py-1.5 text-xs rounded-full border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 <?= $statusVal === '' ? 'bg-gray-100 dark:bg-gray-800/70 font-semibold' : '' ?>">Todos</a>
        <a href="/admin/produtos?status_validade=valido" class="px-3 py-1.5 text-xs rounded-full border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/40 <?= $statusVal === 'valido' ? 'bg-emerald-50 dark:bg-emerald-900/40 font-semibold' : '' ?>">Válidos</a>
        <a href="/admin/produtos?status_validade=proximo" class="px-3 py-1.5 text-xs rounded-full border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/40 <?= $statusVal === 'proximo' ? 'bg-amber-50 dark:bg-amber-900/40 font-semibold' : '' ?>">Próximo do vencimento</a>
        <a href="/admin/produtos?status_validade=vencido" class="px-3 py-1.5 text-xs rounded-full border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-900/40 <?= $statusVal === 'vencido' ? 'bg-rose-50 dark:bg-rose-900/40 font-semibold' : '' ?>">Vencidos</a>
    </div>

    <!-- Lista -->
    <div class="grid gap-4 grid-cols-1 xl:grid-cols-2">
        <?php foreach ($produtos as $prod): 
            $status = strtolower($prod['status'] ?? 'ativo');
            $statusColor = $status === 'critico' ? 'text-red-600 bg-red-50 border-red-200 dark:bg-red-900/40 dark:border-red-800' : ($status === 'alerta' ? 'text-amber-600 bg-amber-50 border-amber-200 dark:bg-amber-900/40 dark:border-amber-800' : 'text-emerald-600 bg-emerald-50 border-emerald-200 dark:bg-emerald-900/40 dark:border-emerald-800');
            $val = $prod['status_validade'] ?? 'indefinido';
            $valBadge = [
                'vencido' => ['txt' => 'Vencido', 'class' => 'text-gray-700 bg-gray-50 border border-gray-200 dark:bg-gray-800/60 dark:border-gray-700'],
                'proximo' => ['txt' => 'Próx. vencimento', 'class' => 'text-gray-700 bg-gray-50 border border-gray-200 dark:bg-gray-800/60 dark:border-gray-700'],
                'valido' => ['txt' => 'Válido', 'class' => 'text-gray-700 bg-gray-50 border border-gray-200 dark:bg-gray-800/60 dark:border-gray-700'],
            ][$val] ?? ['txt' => 'Sem validade', 'class' => 'text-gray-600 bg-gray-50 border border-gray-200 dark:bg-gray-800/60 dark:border-gray-700'];
            $cardBorder = 'border-gray-200 dark:border-gray-800';
        ?>
        <div class="p-4 rounded-2xl border <?= $cardBorder ?> bg-white dark:bg-gray-900 shadow-sm flex flex-col gap-3">
            <div class="flex items-start justify-between gap-3">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($prod['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                        <span class="text-[11px] px-2 py-[3px] rounded-full border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                            <?= htmlspecialchars($prod['categoria'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU <?= htmlspecialchars($prod['sku'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold">R$ <?= htmlspecialchars(number_format((float)($prod['preco'] ?? 0), 2, ',', '.'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] px-2 py-[3px] rounded-full border <?= $statusColor ?>">
                    <i data-lucide="dot" class="w-3 h-3"></i>
                    <?= htmlspecialchars($prod['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                </span>
            </div>

            <div class="grid grid-cols-3 gap-3 text-center text-xs">
                <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60 p-3">
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Loja</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white"><?= (int)($prod['estoque_loja'] ?? 0) ?></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60 p-3">
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Consignado</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white"><?= (int)($prod['estoque_consignado'] ?? 0) ?></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60 p-3">
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Mínimo</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white"><?= (int)($prod['minimo'] ?? 0) ?></p>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-full border <?= $valBadge['class'] ?>">
                        <i data-lucide="calendar-clock" class="w-3 h-3"></i>
                        <?= $valBadge['txt'] ?>
                    </span>
                </span>
                <div class="flex gap-1 items-center">
                    <a href="/admin/produtos/<?= (int)($prod['id'] ?? 0) ?>/ver" class="px-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200">Ver</a>
                    <a href="/admin/produtos/<?= (int)($prod['id'] ?? 0) ?>/editar" class="px-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200">Editar</a>
                    <form action="/admin/produtos/<?= (int)($prod['id'] ?? 0) ?>/toggle" method="POST" class="inline">
                        <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken(); ?>">
                        <?php $ativo = strtolower($prod['status'] ?? 'ativo') !== 'inativo'; ?>
                        <button type="submit" class="px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-800 text-xs <?= $ativo ? 'text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/40' : 'text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/40' ?>">
                            <?= $ativo ? 'Desativar' : 'Ativar' ?>
                        </button>
                    </form>
                    <form action="/admin/produtos/<?= (int)($prod['id'] ?? 0) ?>/excluir" method="POST" class="inline" onsubmit="return confirm('Excluir produto?');">
                        <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken(); ?>">
                        <button type="submit" class="px-3 py-1 rounded-lg border border-gray-200 dark:border-gray-800 text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/40 text-xs">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
