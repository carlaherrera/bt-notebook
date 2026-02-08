<?php
use App\Core\Auth;
use App\Core\Security;

$user = Auth::user();
$title = 'Perfil do Cliente';
$avatarFallback = '/uploads/fallback-images/fallback-avatar.webp';
$userAvatar = $user->imagem_perfil ? Security::sanitizeString($user->imagem_perfil) : $avatarFallback;
$avatarFileName = $user->imagem_perfil ? basename($userAvatar) : 'Nenhum arquivo';
?>

<style>
    .input-box {
        border-color: #e2e8f0;
        box-shadow: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .dark .input-box { border-color: #1f2937; }
    .input-box:focus-within {
        border-color: var(--primary-color);
        box-shadow: inset 0 0 0 1px var(--primary-color);
    }
    .btn-primary {
        background: var(--primary-color);
        color: #fff;
        transition: background 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-primary:hover { background: var(--primary-color-hover); }
</style>

<section class="space-y-6">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Perfil</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Gerencie seus dados e imagem de perfil.</p>
        </div>
    </header>

    <section class="grid gap-6 lg:grid-cols-[320px,1fr]">
    <article class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-900 p-6 shadow-sm">
        <div class="flex flex-col items-center text-center gap-4">
            <div class="relative h-32 w-32">
                <div class="absolute inset-0 rounded-[32px] bg-blue-50 blur-3xl opacity-70 dark:opacity-30"></div>
                <div class="relative flex h-full w-full items-center justify-center overflow-hidden rounded-[32px] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <img src="<?= $userAvatar ?>" alt="Avatar do usuário" class="absolute inset-0 h-full w-full object-cover">
                </div>
            </div>
            <div>
                <p class="text-lg font-semibold text-slate-900 dark:text-white"><?= \App\Core\Security::sanitizeString(trim(($user->nome ?? '') . ' ' . ($user->sobrenome ?? ''))) ?></p>
                <p class="text-sm text-slate-500 dark:text-slate-300"><?= \App\Core\Security::sanitizeString($user->email ?? '') ?></p>
                <?php if (!empty($user->whatsapp)): ?>
                <p class="mt-3 inline-flex items-center gap-2 rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-600 dark:text-slate-200">
                    <i data-lucide="phone" class="h-4 w-4"></i>
                    <?= \App\Core\Security::sanitizeString($user->whatsapp) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-6 space-y-3 text-sm text-slate-500 dark:text-slate-300">
            <p class="flex items-center gap-2"><i data-lucide="shield-check" class="h-4 w-4 text-emerald-500"></i> Acesso ao painel do cliente</p>
            <p class="flex items-center gap-2"><i data-lucide="clock-3" class="h-4 w-4 text-blue-500"></i> Atualizado em tempo real</p>
            <p class="flex items-center gap-2"><i data-lucide="activity" class="h-4 w-4 text-indigo-500"></i> Segurança reforçada</p>
        </div>
    </article>

    <article class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <form action="/cliente/perfil" method="POST" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken() ?>">

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="campo-nome">Nome</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="user" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="campo-nome" type="text" name="nome" value="<?= \App\Core\Security::sanitizeString($user->nome ?? '') ?>" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 shadow-none" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="campo-sobrenome">Sobrenome</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="user-round" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="campo-sobrenome" type="text" name="sobrenome" value="<?= \App\Core\Security::sanitizeString($user->sobrenome ?? '') ?>" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus-outline-none focus:ring-0 shadow-none" placeholder="Seu sobrenome">
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="campo-email">E-mail</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="mail" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="campo-email" type="email" name="email" value="<?= \App\Core\Security::sanitizeString($user->email ?? '') ?>" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 shadow-none" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="campo-whatsapp">WhatsApp</label>
                    <div class="flex items-center gap-3 rounded-2xl border bg-white dark:bg-slate-800 px-4 py-3 shadow-sm input-box transition">
                        <i data-lucide="phone" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="campo-whatsapp" type="text" name="whatsapp" value="<?= \App\Core\Security::sanitizeString($user->whatsapp ?? '') ?>" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus-outline-none focus:ring-0 shadow-none" placeholder="(11) 99999-9999">
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300">Imagem de perfil</label>
                    <button type="button" class="text-xs font-semibold text-rose-500 hover:text-rose-600" onclick="document.getElementById('imagem-perfil-input').value='';">Remover imagem</button>
                </div>
                <div class="flex flex-col gap-4 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/70 p-4 md:flex-row md:items-center">
                    <div class="relative h-28 w-28 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-center">
                        <img src="<?= $userAvatar ?>" alt="Preview da imagem" class="absolute inset-0 h-full w-full object-cover">
                    </div>
                    <div class="flex-1 space-y-3">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Arraste ou selecione um arquivo</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Formatos PNG, JPG ou WEBP com até 5MB.</p>
                        <div class="flex flex-wrap items-center gap-3">
                            <label for="imagem-perfil-input" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-xs font-semibold shadow-sm cursor-pointer btn-primary">
                                <i data-lucide="upload" class="h-4 w-4"></i>
                                Selecionar arquivo
                            </label>
                            <span id="avatarFileName" class="text-xs text-slate-500 dark:text-slate-300 truncate max-w-[220px]">
                                <?= $avatarFileName ?>
                            </span>
                        </div>
                    </div>
                    <input type="file" name="imagem_perfil" accept="image/png, image/jpeg, image/webp, image/svg+xml" class="hidden" id="imagem-perfil-input" onchange="document.getElementById('avatarFileName').innerText = this.files[0]?.name ?? 'Nenhum arquivo';">
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="campo-nova-senha">Nova senha</label>
                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 shadow-sm focus-within:border-blue-300 focus-within:shadow-blue-100 dark:focus-within:border-blue-500/60 dark:focus-within:shadow-blue-900/30 transition">
                        <i data-lucide="lock" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="campo-nova-senha" type="password" name="nova_senha" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 shadow-none" placeholder="Deixe em branco para manter">
                        <button type="button" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300" onclick="togglePassword('campo-nova-senha', this)">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="campo-confirmar-senha">Confirmar nova senha</label>
                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 shadow-sm focus-within:border-blue-300 focus-within:shadow-blue-100 dark:focus-within:border-blue-500/60 dark:focus-within:shadow-blue-900/30 transition">
                        <i data-lucide="lock" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                        <input id="campo-confirmar-senha" type="password" name="confirmar_senha" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus-outline-none focus:ring-0 shadow-none" placeholder="Repita a nova senha">
                        <button type="button" class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300" onclick="togglePassword('campo-confirmar-senha', this)">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-slate-500 dark:text-slate-300">As alterações são auditadas e sincronizadas em tempo real.</p>
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold shadow-sm btn-primary">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Salvar alterações
                </button>
            </div>
        </form>
    </article>
    </section>

</section>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (!input) return;
    const showing = input.type === 'text';
    input.type = showing ? 'password' : 'text';
    btn.querySelector('i')?.setAttribute('data-lucide', showing ? 'eye' : 'eye-off');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
function maskWhatsapp(el) {
    let v = el.value.replace(/\D/g,'').slice(0,11);
    if (v.length > 6) v = v.replace(/(\d{2})(\d{5})(\d{0,4})/,'($1) $2-$3');
    else if (v.length > 2) v = v.replace(/(\d{2})(\d{0,5})/,'($1) $2');
    el.value = v;
}
document.getElementById('campo-whatsapp')?.addEventListener('input', (e)=>maskWhatsapp(e.target));
</script>
