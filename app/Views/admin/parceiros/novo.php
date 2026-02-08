<?php
use App\Core\Security;
?>

<section class="space-y-6">
    <header class="flex items-start justify-between gap-3 flex-wrap">
        <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-[0.18em]">Admin</p>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Novo parceiro</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Cria usuário role parceiro e vincula ao cadastro.</p>
        </div>
        <a href="/admin/parceiros" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] shadow-sm">
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Voltar
        </a>
    </header>

    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-6">
        <form action="/admin/parceiros" method="POST" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="nome">Nome</label>
                    <input id="nome" name="nome" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Nome do parceiro">
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
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                        <option value="academia">Academia</option>
                        <option value="personal">Personal</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="documento">Documento</label>
                    <input id="documento" name="documento" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="CNPJ/CPF">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="cidade">Cidade</label>
                    <input id="cidade" name="cidade" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Cidade/UF">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="contato">Contato</label>
                    <input id="contato" name="contato" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Pessoa de contato">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="telefone">Telefone</label>
                    <input id="telefone" name="telefone" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="(11) 90000-0000">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="ticket_medio">Ticket médio (R$)</label>
                    <input id="ticket_medio" name="ticket_medio" type="number" step="0.01" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="0,00">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="status">Status</label>
                    <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                        <input id="status" type="checkbox" name="status" value="1" class="h-4 w-4 rounded border-slate-300 dark:border-slate-700">
                        Ativo
                    </label>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="senha">Senha</label>
                    <input id="senha" name="senha" type="password" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Defina uma senha">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="senha_confirmacao">Confirmar senha</label>
                    <input id="senha_confirmacao" name="senha_confirmacao" type="password" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Repita a senha">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/admin/parceiros" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-100 hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] hover:text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)] shadow-sm">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[var(--primary-500)] text-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-[var(--primary-600)] transition">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Salvar parceiro
                </button>
            </div>
        </form>
    </section>
</section>
