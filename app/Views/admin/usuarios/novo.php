<?php
use App\Core\Security;
/**
 * Página: Novo Usuário
 */
$title = 'Novo Usuário';
?>

<section class="space-y-6">
    <header class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-[0.18em]">Admin</p>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Novo usuário</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Crie um usuário e defina role e status.</p>
        </div>
        <a href="/admin/usuarios" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] shadow-sm">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Voltar
        </a>
    </header>

    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-6">
        <form action="/admin/usuarios" method="POST" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="nome">Nome</label>
                    <input id="nome" name="nome" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Digite o nome">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="sobrenome">Sobrenome</label>
                    <input id="sobrenome" name="sobrenome" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Sobrenome (opcional)">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="email">E-mail</label>
                    <input id="email" type="email" name="email" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="email@exemplo.com">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="role">Role</label>
                    <select id="role" name="role" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                        <option value="admin">Admin</option>
                        <option value="colaborador">Colaborador</option>
                        <option value="cliente">Cliente</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="senha">Senha</label>
                    <input id="senha" type="password" name="senha" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Defina uma senha">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="senha_confirmacao">Confirmar senha</label>
                    <input id="senha_confirmacao" type="password" name="senha_confirmacao" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Repita a senha">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <label for="ativo" class="relative inline-flex items-center cursor-pointer select-none">
                    <input id="ativo" name="ativo" type="checkbox" value="1" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer-checked:bg-[color-mix(in_srgb,var(--primary-color)_80%,white_20%)] peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[color-mix(in_srgb,var(--primary-color)_60%,white_40%)] transition"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                </label>
                <span class="text-sm text-slate-600 dark:text-slate-300">Usuário ativo</span>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/admin/usuarios" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 text-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-blue-700 transition">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Salvar usuário
                </button>
            </div>
        </form>
    </section>
</section>
