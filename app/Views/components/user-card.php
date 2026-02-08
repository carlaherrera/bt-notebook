<?php
/**
 * User Card Component (Mobile)
 * @var object $user - Objeto do usuário
 * @var string $avatar - URL da imagem
 * @var string $nomeCompleto - Nome completo
 * @var string $email - Email
 * @var string $role - Role
 * @var int $status - Status (1 ou 0)
 * @var string $whats - WhatsApp
 * @var string $criado - Data de criação formatada
 */
use App\Core\Security;

$user = $user ?? null;
$userId = $user->id ?? null;
$nomeCompleto = $nomeCompleto ?? '';
$email = $email ?? '';
$role = $role ?? '';
$status = $status ?? 0;
$whats = $whats ?? '';
$criado = $criado ?? '';
$avatar = $avatar ?? 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($email);
$statusLabel = $status === 1 ? 'Ativo' : 'Inativo';
$statusClass = $status === 1 
    ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-200 dark:border-emerald-800' 
    : 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-900/30 dark:text-rose-200 dark:border-rose-800';
?>
<div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4 shadow-sm" data-name="<?= strtolower($nomeCompleto) ?>" data-email="<?= strtolower($email) ?>" data-role="<?= strtolower($role) ?>" data-status="<?= $status ?>" data-whats="<?= strtolower($whats) ?>">
    <div class="flex items-start gap-3 mb-3">
        <div class="h-10 w-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 flex-shrink-0">
            <img src="<?= $avatar ?>" alt="Avatar" class="w-full h-full object-cover">
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-900 dark:text-white truncate"><?= $nomeCompleto ?: 'Usuário' ?></p>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate"><?= $email ?></p>
        </div>
    </div>
    <div class="space-y-2 mb-3">
        <div class="flex items-center justify-between text-xs">
            <span class="text-slate-500 dark:text-slate-400">Role</span>
            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-semibold text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700">
                <i data-lucide="badge-check" class="h-2.5 w-2.5"></i>
                <?= $role ?>
            </span>
        </div>
        <div class="flex items-center justify-between text-xs">
            <span class="text-slate-500 dark:text-slate-400">Status</span>
            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-xs font-semibold <?= $statusClass ?>">
                <i data-lucide="<?= $status === 1 ? 'check-circle' : 'x-circle' ?>" class="h-2.5 w-2.5"></i>
                <?= $statusLabel ?>
            </span>
        </div>
        <?php if ($whats): ?>
        <div class="flex items-center justify-between text-xs">
            <span class="text-slate-500 dark:text-slate-400">WhatsApp</span>
            <span class="text-slate-700 dark:text-slate-200 font-medium"><?= $whats ?></span>
        </div>
        <?php endif; ?>
        <div class="flex items-center justify-between text-xs">
            <span class="text-slate-500 dark:text-slate-400">Criado em</span>
            <span class="text-slate-700 dark:text-slate-200 font-medium"><?= $criado ?: '—' ?></span>
        </div>
    </div>
    <div class="flex items-center gap-2 pt-3 border-t border-slate-200 dark:border-slate-700">
        <a href="<?= $userId ? '/admin/usuarios/' . $userId : '#' ?>" class="flex-1 inline-flex h-8 items-center justify-center gap-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition text-xs font-medium" title="Ver">
            <i data-lucide="eye" class="h-4 w-4"></i>
            <span>Ver</span>
        </a>
        <a href="<?= $userId ? '/admin/usuarios/' . $userId . '/editar' : '#' ?>" class="flex-1 inline-flex h-8 items-center justify-center gap-2 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 transition text-xs font-medium" title="Editar">
            <i data-lucide="pencil" class="h-4 w-4"></i>
            <span>Editar</span>
        </a>
        <form action="<?= $userId ? '/admin/usuarios/' . $userId . '/toggle' : '#' ?>" method="POST" class="flex-1">
            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
            <?php $isActive = (int)($status ?? 0) === 1; ?>
            <button type="submit" class="w-full inline-flex h-8 items-center justify-center gap-2 rounded-lg <?= $isActive ? 'bg-slate-100 dark:bg-slate-800/60 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/50' ?> transition text-xs font-medium" title="<?= $isActive ? 'Bloquear' : 'Liberar' ?>">
                <i data-lucide="<?= $isActive ? 'lock' : 'unlock' ?>" class="h-4 w-4"></i>
                <span><?= $isActive ? 'Bloquear' : 'Liberar' ?></span>
            </button>
        </form>
    </div>
</div>
