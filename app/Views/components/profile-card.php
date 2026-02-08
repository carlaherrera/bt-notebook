<?php
use App\Core\Security;

/**
 * Espera $user (objeto com nome, email, role, imagem_perfil, whatsapp, created_at)
 * e $title opcional.
 */
$nome = trim(($user->nome ?? '') . ' ' . ($user->sobrenome ?? '')) ?: 'Usuário';
$email = $user->email ?? '';
$role = $user->role ?? '';
$whats = $user->whatsapp ?? '';
$foto = $user->imagem_perfil ?? '/uploads/fallback-images/fallback-avatar.webp';
$criado = $user->created_at ?? '';
$title = $title ?? 'Perfil';
?>

<div class="space-y-4">
    <div class="flex items-center gap-4">
        <img src="<?= Security::sanitizeString($foto) ?>" alt="<?= Security::sanitizeString($nome) ?>" class="w-16 h-16 rounded-2xl object-cover shadow">
        <div>
            <p class="text-xs uppercase text-gray-400 font-semibold"><?= Security::sanitizeString($role) ?></p>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white"><?= Security::sanitizeString($nome) ?></h2>
            <p class="text-gray-500"><?= Security::sanitizeString($email) ?></p>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-400">Contato</p>
            <p class="text-sm text-gray-700 dark:text-gray-200 mt-2 flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4"></i>
                <?= Security::sanitizeString($email) ?>
            </p>
            <?php if ($whats): ?>
            <p class="text-sm text-gray-700 dark:text-gray-200 mt-1 flex items-center gap-2">
                <i data-lucide="phone" class="w-4 h-4"></i>
                <?= Security::sanitizeString($whats) ?>
            </p>
            <?php endif; ?>
        </div>

        <div class="p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-400">Conta</p>
            <p class="text-sm text-gray-700 dark:text-gray-200 mt-2 flex items-center gap-2">
                <i data-lucide="badge-check" class="w-4 h-4"></i>
                Status: <span class="font-semibold text-emerald-600 dark:text-emerald-400">Ativo</span>
            </p>
            <?php if ($criado): ?>
            <p class="text-sm text-gray-700 dark:text-gray-200 mt-1 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                Desde <?= Security::sanitizeString($criado) ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>
