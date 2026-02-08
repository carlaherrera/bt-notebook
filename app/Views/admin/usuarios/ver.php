<?php
use App\Core\Security;
/** @var object $usuario */
$title = 'Usuário';
$nome = Security::sanitizeString($usuario->nome ?? '');
$sobrenome = Security::sanitizeString($usuario->sobrenome ?? '');
$nomeCompleto = trim($nome . ' ' . $sobrenome);
$email = Security::sanitizeString($usuario->email ?? '');
$role = Security::sanitizeString($usuario->role ?? '');
$status = (int)($usuario->status ?? 0);
$statusLabel = $status === 1 ? 'Ativo' : 'Inativo';
$statusIcon = $status === 1 ? 'check-circle' : 'x-circle';
$statusClasses = $status === 1
    ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-200 dark:border-emerald-800'
    : 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-900/30 dark:text-rose-200 dark:border-rose-800';
$avatar = $usuario->imagem_perfil
    ? Security::sanitizeString($usuario->imagem_perfil)
    : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($usuario->email ?? ($usuario->id ?? 'guest'));
$criado = '';
if ($usuario->created_at ?? null) {
    try { $dt = new DateTime($usuario->created_at); $criado = $dt->format('d/m/Y \à\s H:i'); } catch (Exception $e) { $criado = Security::sanitizeString($usuario->created_at); }
}
$atualizado = '';
if ($usuario->updated_at ?? null) {
    try { $dt = new DateTime($usuario->updated_at); $atualizado = $dt->format('d/m/Y \à\s H:i'); } catch (Exception $e) { $atualizado = Security::sanitizeString($usuario->updated_at); }
}
?>

<section class="space-y-6">
    <header class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-[0.18em]">Admin</p>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Usuário</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Resumo, status e acessos.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="/admin/usuarios/<?= $usuario->id ?>/editar" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold shadow-sm transition" style="background: var(--primary-color); color: #fff;" onmouseover="this.style.background='var(--primary-color-hover)';" onmouseout="this.style.background='var(--primary-color)';">
                <i data-lucide="pencil" class="h-4 w-4"></i>
                Editar
            </a>
            <a href="/admin/usuarios" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] shadow-sm">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </header>

    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="flex-1 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 p-4 sm:p-5 shadow-sm">
                <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
                    <div class="h-16 w-16 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800">
                        <img src="<?= $avatar ?>" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 space-y-1">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white"><?= $nomeCompleto ?: 'Usuário' ?></h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 flex items-center gap-2"><i data-lucide="mail" class="h-4 w-4"></i> <?= $email ?></p>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700">
                                <i data-lucide="badge-check" class="h-3 w-3"></i>
                                <?= $role ?: '—' ?>
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-semibold <?= $statusClasses ?>">
                                <i data-lucide="<?= $statusIcon ?>" class="h-3 w-3"></i>
                                <?= $statusLabel ?>
                            </span>
                        </div>
                    </div>
                    <form action="/admin/usuarios/<?= $usuario->id ?>/toggle" method="POST" class="flex-shrink-0">
                        <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                        <?php $isActive = $status === 1; ?>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold shadow-sm border border-transparent <?= $isActive ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50' : 'bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 hover:bg-rose-100 dark:hover:bg-rose-900/50' ?>">
                            <i data-lucide="<?= $isActive ? 'unlock' : 'lock' ?>" class="h-4 w-4"></i>
                            <?= $isActive ? 'Bloquear' : 'Liberar' ?>
                        </button>
                    </form>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 p-3 flex items-center gap-3 shadow-sm">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: color-mix(in srgb, var(--primary-color) 16%, white);">
                            <i data-lucide="calendar" class="h-5 w-5 text-[color-mix(in_srgb,var(--primary-color)_75%,black_25%)]"></i>
                        </div>
                        <div class="text-sm">
                            <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Criado</p>
                            <p class="text-[12px] text-slate-900 dark:text-slate-100 font-semibold"><?= $criado ?: '—' ?></p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 p-3 flex items-center gap-3 shadow-sm">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: color-mix(in srgb, var(--primary-color) 16%, white);">
                            <i data-lucide="refresh-ccw" class="h-5 w-5 text-[color-mix(in_srgb,var(--primary-color)_75%,black_25%)]"></i>
                        </div>
                        <div class="text-sm">
                            <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Atualizado</p>
                            <p class="text-[12px]  text-slate-900 dark:text-slate-100 font-semibold"><?= $atualizado ?: '—' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:max-w-xs space-y-3">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 p-4 shadow-sm">
                    <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold mb-3">Ações rápidas</p>
                    <div class="grid gap-2">
                        <a href="mailto:<?= $email ?>" class="flex items-center justify-between rounded-xl px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)]">
                            <span class="flex items-center gap-2 text-slate-700 dark:text-slate-200"><i data-lucide="mail" class="h-4 w-4"></i>Email</span>
                            <i data-lucide="chevron-right" class="h-4 w-4 text-slate-400"></i>
                        </a>
                        <a href="/admin/usuarios/<?= $usuario->id ?>/editar" class="flex items-center justify-between rounded-xl px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)]">
                            <span class="flex items-center gap-2 text-slate-700 dark:text-slate-200"><i data-lucide="pencil" class="h-4 w-4"></i>Editar perfil</span>
                            <i data-lucide="chevron-right" class="h-4 w-4 text-slate-400"></i>
                        </a>
                        <form action="/admin/usuarios/<?= $usuario->id ?>/toggle" method="POST">
                            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                            <?php $isActive = $status === 1; ?>
                            <button type="submit" class="w-full flex items-center justify-between rounded-xl px-3 py-2 text-sm border <?= $isActive ? 'border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 hover:border-emerald-300 dark:hover:border-emerald-600' : 'border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 hover:border-rose-300 dark:hover:border-rose-600' ?>">
                                <span class="flex items-center gap-2"><i data-lucide="<?= $isActive ? 'unlock' : 'lock' ?>" class="h-4 w-4"></i><?= $isActive ? 'Bloquear acesso' : 'Liberar acesso' ?></span>
                                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 p-4 shadow-sm">
                    <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold mb-3">Detalhes</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400">ID</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100"><?= $usuario->id ?? '—' ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Status</span>
                            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-semibold <?= $statusClasses ?>">
                                <i data-lucide="<?= $statusIcon ?>" class="h-3 w-3"></i><?= $statusLabel ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Papel</span>
                            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-semibold text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700"><?= $role ?: '—' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/80 p-4 shadow-sm">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nome completo</p>
                    <p class="text-sm text-slate-900 dark:text-slate-50"><?= $nomeCompleto ?: '—' ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Email</p>
                    <p class="text-sm text-slate-900 dark:text-slate-50 flex items-center gap-2"><i data-lucide="mail" class="h-4 w-4"></i> <?= $email ?: '—' ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Role</p>
                    <p class="text-sm text-slate-900 dark:text-slate-50"><?= $role ?: '—' ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</p>
                    <p class="text-sm text-slate-900 dark:text-slate-50 flex items-center gap-2"><i data-lucide="<?= $statusIcon ?>" class="h-4 w-4"></i> <?= $statusLabel ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Criado em</p>
                    <p class="text-sm text-slate-900 dark:text-slate-50"><?= $criado ?: '—' ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Atualizado em</p>
                    <p class="text-sm text-slate-900 dark:text-slate-50"><?= $atualizado ?: '—' ?></p>
                </div>
            </div>
        </div>
    </section>
</section>
