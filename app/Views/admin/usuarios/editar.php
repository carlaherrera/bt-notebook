<?php
use App\Core\Security;
/** @var object $usuario */
$title = 'Editar usuário';
$nome = Security::sanitizeString($usuario->nome ?? '');
$sobrenome = Security::sanitizeString($usuario->sobrenome ?? '');
$email = Security::sanitizeString($usuario->email ?? '');
$role = Security::sanitizeString($usuario->role ?? '');
$status = (int)($usuario->status ?? 0);
?>

<style>
    .input-box {
        border-color: #e2e8f0;
        box-shadow: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .dark .input-box {
        border-color: #1f2937;
    }
    .input-box:focus-within {
        border-color: var(--primary-color);
        box-shadow: inset 0 0 0 1px var(--primary-color);
    }
</style>

<section class="space-y-6">
    <header class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-[0.18em]">Admin</p>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Editar usuário</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Atualize dados, role e status.</p>
        </div>
        <div class="flex gap-2 flex-wrap justify-end">
            <a href="/admin/usuarios/<?= (int)$usuario->id ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-blue-500 hover:text-blue-600 dark:hover:border-blue-500/60 shadow-sm">
                <i data-lucide="eye" class="h-4 w-4"></i>
                Visualizar
            </a>
            <a href="/admin/usuarios" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-blue-500 hover:text-blue-600 dark:hover:border-blue-500/60 shadow-sm">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Voltar
            </a>
        </div>
    </header>

    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-6">
        <form action="/admin/usuarios/<?= (int)$usuario->id ?>/editar" method="POST" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="nome">Nome</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="user" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="nome" name="nome" required value="<?= $nome ?>" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 shadow-none" placeholder="Digite o nome">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="sobrenome">Sobrenome</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="user-round" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="sobrenome" name="sobrenome" value="<?= $sobrenome ?>" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 shadow-none" placeholder="Sobrenome (opcional)">
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="email">E-mail</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="mail" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="email" type="email" name="email" required value="<?= $email ?>" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 shadow-none" placeholder="email@exemplo.com">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="role">Role</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="shield" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <select id="role" name="role" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 shadow-none">
                            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="colaborador" <?= $role === 'colaborador' ? 'selected' : '' ?>>Colaborador</option>
                            <option value="cliente" <?= $role === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label for="ativo" class="relative inline-flex items-center cursor-pointer select-none">
                    <input id="ativo" name="ativo" type="checkbox" value="1" class="sr-only peer" <?= $status === 1 ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer-checked:bg-[color-mix(in_srgb,var(--primary-color)_80%,white_20%)] peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[color-mix(in_srgb,var(--primary-color)_60%,white_40%)] transition"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                </label>
                <span class="text-sm text-slate-600 dark:text-slate-300">Usuário ativo</span>
            </div>

            <div class="flex justify-end gap-3 flex-wrap">
                <a href="/admin/usuarios" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 text-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-blue-700 transition">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Salvar alterações
                </button>
            </div>
        </form>
    </section>
</section>
