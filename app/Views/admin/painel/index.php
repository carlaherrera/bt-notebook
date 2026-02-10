<?php
// /app/Views/admin/painel/index.php
include __DIR__ . '/../../components/button.php';
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

    @media (prefers-color-scheme: dark) {
        :root {
            --primary-50: color-mix(in srgb, var(--primary-color) 18%, #0b0f16);
            --primary-100: color-mix(in srgb, var(--primary-color) 26%, #0b0f16);
            --primary-200: color-mix(in srgb, var(--primary-color) 34%, #0b0f16);
            --primary-500: color-mix(in srgb, var(--primary-color) 92%, white 8%);
            --primary-600: color-mix(in srgb, var(--primary-color) 96%, white 4%);
            --primary-700: color-mix(in srgb, var(--primary-color) 100%, white 10%);
        }
    }

    .pill-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #0b0f16 !important;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 600;
    }

    .dark .pill-primary {
        border-color: #e5e7eb !important;
        background: #ffffff !important;
        color: #0b0f16 !important;
    }

    .icon-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        padding: 6px;
        background: #ffffff !important;
        color: #0b0f16 !important;
        border: 1px solid #e5e7eb !important;
    }

    .dark .icon-badge {
        background: #ffffff !important;
        color: #0b0f16 !important;
        border-color: #e5e7eb !important;
    }

    .tag-ghost {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 12px;
        background: #ffffff !important;
        color: #0b0f16 !important;
        border: 1px solid #e5e7eb !important;
        font-weight: 600;
        font-size: 12px;
    }

    .dark .tag-ghost {
        background: #ffffff !important;
        color: #0b0f16 !important;
        border-color: #e5e7eb !important;
    }

    /* Ícones e textos dentro dos itens claros devem ser escuros mesmo no tema dark */
    .pill-primary i[data-lucide],
    .pill-primary svg,
    .icon-badge i[data-lucide],
    .icon-badge svg,
    .tag-ghost i[data-lucide],
    .tag-ghost svg {
        color: #0b0f16 !important;
        stroke: #0b0f16 !important;
    }

    .pill-primary, .icon-badge, .tag-ghost {
        color: #0b0f16 !important;
    }

    /* Qualquer texto filho de componentes claros herda cor escura */
    .pill-primary *, .icon-badge *, .tag-ghost * {
        color: #0b0f16 !important;
        stroke: #0b0f16 !important;
    }
</style>

<section class="space-y-6">

    <header class="relative overflow-hidden rounded-3xl border border-gray-200/70 dark:border-gray-800 bg-white dark:bg-gray-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <div class="tag-ghost">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Painel admin
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Painel</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Visão geral e ações rápidas para o time.</p>
            </div>

            <div class="flex gap-2 flex-wrap">
                <a href="/admin/usuarios" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    Gerenciar usuários
                </a>
                <a href="/admin/configuracoes" class="inline-flex items-center gap-2 rounded-xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold hover:bg-[var(--primary-600)] transition-colors">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    Configurações
                </a>
            </div>
        </div>

        <div class="pointer-events-none absolute inset-0 opacity-25 [mask-image:radial-gradient(circle_at_80%_20%,black,transparent_60%)]" aria-hidden="true">
            <div class="absolute -top-8 right-12 h-32 w-32 rounded-full bg-[var(--primary-100)] blur-2xl"></div>
            <div class="absolute top-10 right-24 h-20 w-20 rounded-full bg-[var(--primary-50)] blur-xl"></div>
        </div>
    </header>

    <!-- Validação de Componente de Botão -->
    <div class="p-6 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/70 dark:border-gray-800 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Validação de Componentes de Botão</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Teste de contraste WCAG 2.1 AA em tema light e dark</p>
        
        <div class="space-y-8">
            <!-- SOLID STYLE -->
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Solid (Preenchido)</h3>
                <div class="space-y-3">
                    <!-- Primary Solid -->
                    <div>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Primary</p>
                        <div class="flex flex-wrap gap-2">
                            <?= renderButton(['text' => 'Salvar', 'variant' => 'primary', 'style' => 'solid', 'icon' => 'save', 'size' => 'sm']) ?>
                            <?= renderButton(['text' => 'Salvar', 'variant' => 'primary', 'style' => 'solid', 'icon' => 'save']) ?>
                            <?= renderButton(['text' => 'Salvar', 'variant' => 'primary', 'style' => 'solid', 'icon' => 'save', 'size' => 'lg']) ?>
                            <?= renderButton(['text' => 'Desabilitado', 'variant' => 'primary', 'style' => 'solid', 'disabled' => true]) ?>
                        </div>
                    </div>
                    <!-- Secondary Solid -->
                    <div>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Secondary</p>
                        <div class="flex flex-wrap gap-2">
                            <?= renderButton(['text' => 'Editar', 'variant' => 'secondary', 'style' => 'solid', 'icon' => 'pencil', 'size' => 'sm']) ?>
                            <?= renderButton(['text' => 'Editar', 'variant' => 'secondary', 'style' => 'solid', 'icon' => 'pencil']) ?>
                            <?= renderButton(['text' => 'Editar', 'variant' => 'secondary', 'style' => 'solid', 'icon' => 'pencil', 'size' => 'lg']) ?>
                            <?= renderButton(['text' => 'Desabilitado', 'variant' => 'secondary', 'style' => 'solid', 'disabled' => true]) ?>
                        </div>
                    </div>
                    <!-- Danger Solid -->
                    <div>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Danger</p>
                        <div class="flex flex-wrap gap-2">
                            <?= renderButton(['text' => 'Deletar', 'variant' => 'danger', 'style' => 'solid', 'icon' => 'trash-2', 'size' => 'sm']) ?>
                            <?= renderButton(['text' => 'Deletar', 'variant' => 'danger', 'style' => 'solid', 'icon' => 'trash-2']) ?>
                            <?= renderButton(['text' => 'Deletar', 'variant' => 'danger', 'style' => 'solid', 'icon' => 'trash-2', 'size' => 'lg']) ?>
                            <?= renderButton(['text' => 'Desabilitado', 'variant' => 'danger', 'style' => 'solid', 'disabled' => true]) ?>
                        </div>
                    </div>
                    <!-- Success Solid -->
                    <div>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Success</p>
                        <div class="flex flex-wrap gap-2">
                            <?= renderButton(['text' => 'Confirmar', 'variant' => 'success', 'style' => 'solid', 'icon' => 'check', 'size' => 'sm']) ?>
                            <?= renderButton(['text' => 'Confirmar', 'variant' => 'success', 'style' => 'solid', 'icon' => 'check']) ?>
                            <?= renderButton(['text' => 'Confirmar', 'variant' => 'success', 'style' => 'solid', 'icon' => 'check', 'size' => 'lg']) ?>
                            <?= renderButton(['text' => 'Desabilitado', 'variant' => 'success', 'style' => 'solid', 'disabled' => true]) ?>
                        </div>
                    </div>
                    <!-- Warning Solid -->
                    <div>
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2">Warning</p>
                        <div class="flex flex-wrap gap-2">
                            <?= renderButton(['text' => 'Atenção', 'variant' => 'warning', 'style' => 'solid', 'icon' => 'alert-circle', 'size' => 'sm']) ?>
                            <?= renderButton(['text' => 'Atenção', 'variant' => 'warning', 'style' => 'solid', 'icon' => 'alert-circle']) ?>
                            <?= renderButton(['text' => 'Atenção', 'variant' => 'warning', 'style' => 'solid', 'icon' => 'alert-circle', 'size' => 'lg']) ?>
                            <?= renderButton(['text' => 'Desabilitado', 'variant' => 'warning', 'style' => 'solid', 'disabled' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OUTLINE STYLE -->
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Outline (Borda)</h3>
                <div class="flex flex-wrap gap-2">
                    <?= renderButton(['text' => 'Primary', 'variant' => 'primary', 'style' => 'outline', 'icon' => 'plus']) ?>
                    <?= renderButton(['text' => 'Secondary', 'variant' => 'secondary', 'style' => 'outline', 'icon' => 'plus']) ?>
                    <?= renderButton(['text' => 'Danger', 'variant' => 'danger', 'style' => 'outline', 'icon' => 'plus']) ?>
                    <?= renderButton(['text' => 'Success', 'variant' => 'success', 'style' => 'outline', 'icon' => 'plus']) ?>
                    <?= renderButton(['text' => 'Warning', 'variant' => 'warning', 'style' => 'outline', 'icon' => 'plus']) ?>
                </div>
            </div>

            <!-- COMO LINK -->
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Como Link</h3>
                <div class="flex flex-wrap gap-2">
                    <?= renderButton(['text' => 'Ir para usuários', 'variant' => 'primary', 'style' => 'solid', 'icon' => 'users', 'href' => '/admin/usuarios']) ?>
                    <?= renderButton(['text' => 'Configurações', 'variant' => 'secondary', 'style' => 'outline', 'icon' => 'settings', 'href' => '/admin/configuracoes']) ?>
                </div>
            </div>

            <!-- SUBMIT (para formulários) -->
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Submit (Formulários)</h3>
                <div class="flex flex-wrap gap-2">
                    <?= renderButton(['text' => 'Salvar', 'variant' => 'primary', 'style' => 'solid', 'icon' => 'save', 'type' => 'submit']) ?>
                    <?= renderButton(['text' => 'Enviar', 'variant' => 'success', 'style' => 'solid', 'icon' => 'send', 'type' => 'submit']) ?>
                    <?= renderButton(['text' => 'Cancelar', 'variant' => 'secondary', 'style' => 'outline', 'icon' => 'x', 'type' => 'button']) ?>
                    <?= renderButton(['text' => 'Deletar', 'variant' => 'danger', 'style' => 'solid', 'icon' => 'trash-2', 'type' => 'submit']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-4 grid-cols-1 lg:grid-cols-3">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/70 dark:border-gray-800 shadow-sm space-y-2 lg:col-span-3 xl:col-span-3">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="pill-primary text-xs">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    Status do sistema
                </div>
                <div class="flex gap-2 text-[11px] text-gray-500 dark:text-gray-400 flex-wrap">
                    <span class="tag-ghost">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        Segurança OK
                    </span>
                    <span class="tag-ghost">
                        <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                        Atualizado há 5min
                    </span>
                    <span class="tag-ghost">
                        <i data-lucide="server" class="w-4 h-4"></i>
                        Produção · v2
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Atalhos rápidos -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <a href="/admin/usuarios" class="p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <div>
                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Usuários</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">Gerenciar perfis</p>
            </div>
            <span class="icon-badge"><i data-lucide="users" class="w-5 h-5"></i></span>
        </a>
        <a href="/admin/movimentacoes" class="p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <div>
                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Movimentações</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1 inline-flex items-center gap-2">
                    <i data-lucide="arrow-left-right" class="w-5 h-5 text-gray-900 dark:text-white"></i>
                    Fluxo de estoque
                </p>
            </div>
            <span class="icon-badge"><i data-lucide="arrow-left-right" class="w-5 h-5"></i></span>
        </a>
        <a href="/admin/consignado" class="p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <div>
                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Parceiros</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">Consignado</p>
            </div>
            <span class="icon-badge"><i data-lucide="handshake" class="w-5 h-5"></i></span>
        </a>
        <a href="/admin/auditoria" class="p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800 transition">
            <div>
                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Auditoria</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">Conferências</p>
            </div>
            <span class="icon-badge"><i data-lucide="check-circle" class="w-5 h-5"></i></span>
        </a>
    </div>

    <!-- KPIs -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-5">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Usuários totais</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($stats['totalUsuarios'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Contagem geral</p>
                </div>
                <span class="w-10 h-10 rounded-xl flex items-center justify-center icon-badge">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Ativos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($stats['ativos'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ativo</p>
                </div>
                <span class="w-10 h-10 rounded-xl flex items-center justify-center icon-badge">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Inativos</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($stats['inativos'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Inativo</p>
                </div>
                <span class="w-10 h-10 rounded-xl flex items-center justify-center icon-badge">
                    <i data-lucide="user-x" class="w-5 h-5"></i>
                </span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Usuários online</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1"><?= (int)($stats['online'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ativos nos últimos 15 min</p>
                </div>
                <span class="w-10 h-10 rounded-xl flex items-center justify-center icon-badge">
                    <i data-lucide="wifi" class="w-5 h-5"></i>
                </span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Admins / Colabs / Clientes</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                        <?= (int)($stats['roles']['admin'] ?? 0) ?> /
                        <?= (int)($stats['roles']['colaborador'] ?? 0) ?> /
                        <?= (int)($stats['roles']['cliente'] ?? 0) ?>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Distribuição por perfil</p>
                </div>
                <span class="w-10 h-10 rounded-xl flex items-center justify-center icon-badge">
                    <i data-lucide="pie-chart" class="w-5 h-5"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Cards principais -->
    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <!-- Usuários recentes -->
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Usuários recentes</h3>
            </div>
            <div class="space-y-3">
                <?php if (!empty($recentUsers)): ?>
                    <?php foreach ($recentUsers as $user): ?>
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-700/70 bg-gray-50/60 dark:bg-gray-900/60">
                            <span class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 flex items-center justify-center">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    <?= htmlspecialchars($user['nome'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                    <span class="text-[11px] ml-2 px-2 py-[2px] rounded-full border pill-primary border-0">
                                        <?= htmlspecialchars($user['role'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                    </span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    <?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </p>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                <?= htmlspecialchars(substr((string)($user['created_at'] ?? ''), 0, 16), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Nenhum usuário recente.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Snapshot de perfis -->
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
            <div>
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Distribuição de perfis</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    <?= (int)($stats['totalUsuarios'] ?? 0) ?> usuários
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Admin / Colaborador / Cliente</p>
            </div>

            <?php
                $progresso = [
                    ['label' => 'Admin', 'valor' => (int)($stats['roles']['admin'] ?? 0), 'cor' => '--primary-700'],
                    ['label' => 'Colaborador', 'valor' => (int)($stats['roles']['colaborador'] ?? 0), 'cor' => '--primary-500'],
                    ['label' => 'Cliente', 'valor' => (int)($stats['roles']['cliente'] ?? 0), 'cor' => '--primary-200'],
                ];
                $total = max(1, (int)($stats['totalUsuarios'] ?? 0));
            ?>

            <div class="space-y-2">
                <?php foreach ($progresso as $p): 
                    $perc = round(($p['valor'] / $total) * 100, 1);
                ?>
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                            <span><?= htmlspecialchars($p['label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                            <span><?= htmlspecialchars((string)$p['valor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> (<?= htmlspecialchars((string)$perc, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>%)</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-700 mt-1">
                            <div class="h-2 rounded-full" style="width: <?= htmlspecialchars((string)$perc, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>%; background: var(<?= htmlspecialchars($p['cor'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>);"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-100 dark:border-gray-700/70">
                <span class="w-10 h-10 rounded-lg icon-badge">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Ativos vs Inativos</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <?= (int)($stats['ativos'] ?? 0) ?> ativos • <?= (int)($stats['inativos'] ?? 0) ?> inativos
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos e anexos -->
    <div class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <!-- Gráfico simples de cadastros (últimos 7 dias) -->
        <div class="xl:col-span-2 p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Cadastros nos últimos 7 dias</h3>
            </div>
            <?php
                $activity = $stats['activity'] ?? [];
                // normaliza para [label, total]
                $maxBar = 1;
                foreach ($activity as $a) {
                    $maxBar = max($maxBar, (int)($a['total'] ?? 0));
                }
            ?>
            <div class="grid grid-cols-7 gap-2">
                <?php foreach ($activity as $a): 
                    $total = (int)($a['total'] ?? 0);
                    $dia = (string)($a['dia'] ?? '');
                    $height = $maxBar > 0 ? max(8, ($total / $maxBar) * 120) : 8;
                ?>
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-full rounded-lg bg-[var(--primary-100)] dark:bg-[var(--primary-50)] flex items-end justify-center" style="height: 140px;">
                            <div class="w-full rounded-lg" style="height: <?= htmlspecialchars((string)$height, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>px; background: var(--primary-600);"></div>
                        </div>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 truncate w-full text-center"><?= htmlspecialchars(substr($dia, 5), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                        <span class="text-[11px] text-gray-600 dark:text-gray-300 font-semibold"><?= htmlspecialchars((string)$total, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($activity)): ?>
                    <div class="col-span-7 text-sm text-gray-500 dark:text-gray-400">Sem dados de cadastro nos últimos dias.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Anexos recentes -->
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Anexos recentes</h3>
                <span class="text-[11px] px-2 py-[2px] rounded-full border"
                      style="border-color: var(--primary-100); color: var(--primary-700); background: var(--primary-50);">
                    <?= (int)($stats['attachmentsTotal'] ?? 0) ?> no total
                </span>
            </div>
            <div class="space-y-3">
                <?php
                    $attachmentsRecent = $stats['attachmentsRecent'] ?? [];
                    $formatSize = function (?int $bytes): string {
                        $bytes = (int)$bytes;
                        if ($bytes >= 1_000_000) return round($bytes/1_000_000, 2).' MB';
                        if ($bytes >= 1_000) return round($bytes/1_000, 2).' KB';
                        return $bytes . ' B';
                    };
                ?>
                <?php if (!empty($attachmentsRecent)): ?>
                    <?php foreach ($attachmentsRecent as $att): ?>
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 dark:border-gray-700/70 bg-gray-50/60 dark:bg-gray-900/60">
                            <span class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 flex items-center justify-center">
                                <i data-lucide="paperclip" class="w-4 h-4"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    <?= htmlspecialchars($att['filename'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    <?= htmlspecialchars($att['mime_type'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($formatSize((int)($att['size'] ?? 0)), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                    <?= htmlspecialchars(substr((string)($att['created_at'] ?? ''), 0, 16), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Nenhum anexo recente.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Configuração e logs -->
    <div class="grid gap-4 grid-cols-1 xl:grid-cols-2">
        <!-- Configuração básica -->
        <div class="p-5 rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 shadow-md space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200 dark:bg-gray-800 dark:text-gray-50 dark:border-gray-700">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    Configuração
                </div>
                <a href="/admin/configuracoes" class="text-xs font-semibold text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)] dark:text-gray-100 hover:underline">Abrir</a>
            </div>
            <?php $settings = $stats['settings'] ?? null; ?>
            <?php if ($settings): ?>
                <div class="grid gap-3 text-sm text-gray-800 dark:text-gray-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="text-gray-500 dark:text-gray-400">Org</div>
                        <div class="font-semibold text-right leading-tight"><?= htmlspecialchars($settings['org_name'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="text-gray-500 dark:text-gray-400">Favicon</div>
                        <div class="text-right leading-tight truncate max-w-[260px]">
                            <?= htmlspecialchars($settings['favicon_path'] ?? '-', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 dark:bg-gray-800/70 px-3 py-2 border border-gray-100 dark:border-gray-800">
                            <span>Criado</span>
                            <span class="font-medium text-gray-700 dark:text-gray-200"><?= htmlspecialchars(substr((string)($settings['created_at'] ?? ''), 0, 16), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 dark:bg-gray-800/70 px-3 py-2 border border-gray-100 dark:border-gray-800">
                            <span>Atualizado</span>
                            <span class="font-medium text-gray-700 dark:text-gray-200"><?= htmlspecialchars(substr((string)($settings['updated_at'] ?? ''), 0, 16), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-sm text-gray-500 dark:text-gray-400">Nenhuma configuração encontrada.</div>
            <?php endif; ?>
        </div>

        <!-- Logs recentes -->
        <div class="p-5 rounded-3xl bg-white dark:bg-gray-900 border border-gray-200/80 dark:border-gray-800 shadow-md space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200 dark:bg-gray-800 dark:text-gray-50 dark:border-gray-700">
                    <i data-lucide="scroll-text" class="w-4 h-4"></i>
                    Logs de hoje
                </div>
                <span class="text-[11px] text-gray-500 dark:text-gray-400">auto-refresh na próxima carga</span>
            </div>
            <?php $logs = $stats['logs'] ?? []; ?>
            <div class="space-y-2 max-h-64 overflow-auto text-xs font-mono text-gray-800 dark:text-gray-100 bg-gray-50 dark:bg-gray-900/60 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $line): ?>
                        <div class="break-words leading-relaxed">
                            <?= htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-gray-500 dark:text-gray-400">Sem logs recentes.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</section>
