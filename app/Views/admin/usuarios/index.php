<?php
use App\Core\Security;
/**
 * @var array $usuarios
 */
$title = 'Usuários';
$total = count($usuarios);
$admins = array_filter($usuarios, fn($u) => ($u->role ?? '') === 'admin');
$colabs = array_filter($usuarios, fn($u) => ($u->role ?? '') === 'colaborador');
$clientes = array_filter($usuarios, fn($u) => ($u->role ?? '') === 'cliente');
$ativos = array_filter($usuarios, fn($u) => (int)($u->status ?? 0) === 1);
$inativos = $total - count($ativos);
?>

<section class="space-y-6" x-data>
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Usuários</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Gerencie contas, roles e status.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="/admin/usuarios/novo" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 text-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-blue-700 hover:text-white">
                <i data-lucide="user-plus" class="h-4 w-4"></i>
                Novo usuário
            </a>
            <a href="/admin/perfil" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-semibold text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)] dark:text-[color-mix(in_srgb,var(--primary-color)_80%,white_20%)] hover:border-[color-mix(in_srgb,var(--primary-color)_45%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_45%,transparent)] hover:text-[color-mix(in_srgb,var(--primary-color)_90%,black_10%)] dark:hover:text-[color-mix(in_srgb,var(--primary-color)_85%,white_15%)] shadow-sm">
                <i data-lucide="user" class="h-4 w-4"></i>
                Meu perfil
            </a>
        </div>
    </header>

    <?php include __DIR__ . '/../../components/metrics-bar.php'; ?>

    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-5 flex flex-col gap-3">
        <div class="grid gap-3 md:grid-cols-[2fr,1fr,1fr]">
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 shadow-sm focus-within:border-slate-300 dark:focus-within:border-slate-500 focus-within:ring-1 focus-within:ring-slate-200/70 dark:focus-within:ring-slate-700/50 transition">
                <i data-lucide="search" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                <input id="filtro-texto" type="text" class="flex-1 bg-transparent text-sm text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500 border-0 focus:outline-none focus:ring-0 focus:border-0 shadow-none" placeholder="Buscar por nome, email ou telefone">
            </div>
            <?php
            $id = 'filtro-role';
            $icon = 'shield';
            $label = 'Todas as roles';
            $options = [
                ['value' => '', 'label' => 'Todas as roles', 'icon' => 'shield'],
                ['value' => 'admin', 'label' => 'Admin', 'icon' => 'shield'],
                ['value' => 'colaborador', 'label' => 'Colaborador', 'icon' => 'briefcase'],
                ['value' => 'cliente', 'label' => 'Cliente', 'icon' => 'user'],
            ];
            include __DIR__ . '/../../components/custom-select.php';
            ?>
            <?php
            $id = 'filtro-status';
            $icon = 'circle-dot';
            $label = 'Todos status';
            $options = [
                ['value' => '', 'label' => 'Todos status', 'icon' => 'circle-dot'],
                ['value' => '1', 'label' => 'Ativo', 'icon' => 'check-circle'],
                ['value' => '0', 'label' => 'Inativo', 'icon' => 'x-circle'],
            ];
            include __DIR__ . '/../../components/custom-select.php';
            ?>
        </div>
    </section>

    <!-- Desktop Table View -->
    <div class="hidden sm:block overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-300">
                <i data-lucide="users" class="h-4 w-4"></i>
                <span><?= count($usuarios) ?> usuários</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 bg-[color-mix(in_srgb,var(--primary-color)_12%,white)] dark:bg-[color-mix(in_srgb,var(--primary-color)_16%,transparent)] text-[color-mix(in_srgb,var(--primary-color)_85%,black_15%)] dark:text-[color-mix(in_srgb,var(--primary-color)_80%,white_20%)] border border-[color-mix(in_srgb,var(--primary-color)_30%,white)] dark:border-[color-mix(in_srgb,var(--primary-color)_30%,transparent)]">
                    <i data-lucide="shield" class="h-3 w-3"></i> <span class="hidden sm:inline">Roles: admin / colaborador / cliente</span><span class="sm:hidden">3 roles</span>
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Usuário</th>
                        <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Role</th>
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Status</th>
                        <th class="hidden lg:table-cell px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">WhatsApp</th>
                        <th class="hidden xl:table-cell px-6 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Criado em</th>
                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 bg-white dark:bg-slate-900">
                    <?php foreach ($usuarios as $u): ?>
                        <?php
                            $nomeCompleto = trim(Security::sanitizeString(($u->nome ?? '') . ' ' . ($u->sobrenome ?? '')));
                            $email = Security::sanitizeString($u->email ?? '');
                            $role = Security::sanitizeString($u->role ?? '');
                            $status = (int) ($u->status ?? 0);
                            $statusLabel = $status === 1 ? 'Ativo' : 'Inativo';
                            $statusClass = $status === 1 ? 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-200 dark:border-emerald-800' : 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-900/30 dark:text-rose-200 dark:border-rose-800';
                            $whats = Security::sanitizeString($u->whatsapp ?? '');
                            $criado = '';
                            if ($u->created_at ?? null) {
                                try {
                                    $dt = new DateTime($u->created_at);
                                    $criado = $dt->format('d/m/Y \á\s H:i');
                                } catch (Exception $e) {
                                    $criado = Security::sanitizeString($u->created_at);
                                }
                            }
                            $avatarFallback = '/uploads/fallback-images/fallback-avatar.webp';
                            $avatar = $u->imagem_perfil
                                ? Security::sanitizeString($u->imagem_perfil)
                                : $avatarFallback;
                        ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors" data-name="<?= strtolower($nomeCompleto) ?>" data-email="<?= strtolower($email) ?>" data-role="<?= strtolower($role) ?>" data-status="<?= $status ?>" data-whats="<?= strtolower($whats) ?>">
                            <td class="px-3 sm:px-6 py-4">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 flex-shrink-0">
                                        <img src="<?= $avatar ?>" alt="Avatar" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-semibold text-slate-900 dark:text-white truncate"><?= $nomeCompleto ?: 'Usuário' ?></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate"><?= $email ?></p>
                                        <div class="sm:hidden flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center gap-0.5 rounded-full border px-2 py-0.5 text-xs font-semibold text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700">
                                                <i data-lucide="badge-check" class="h-2.5 w-2.5"></i>
                                                <?= $role ?>
                                            </span>
                                            <span class="inline-flex items-center gap-0.5 rounded-full border px-2 py-0.5 text-xs font-semibold <?= $statusClass ?>">
                                                <i data-lucide="<?= $status === 1 ? 'check-circle' : 'x-circle' ?>" class="h-2.5 w-2.5"></i>
                                                <?= $statusLabel ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4">
                                <?php
                                $size = 'md';
                                include __DIR__ . '/../../components/role-chip.php';
                                ?>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                <?php
                                unset($label);
                                include __DIR__ . '/../../components/status-badge.php';
                                ?>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4">
                                <div class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                                    <i data-lucide="phone" class="h-4 w-4 text-slate-400 dark:text-slate-500"></i>
                                    <span><?= $whats ?: '—' ?></span>
                                </div>
                            </td>
                            <td class="hidden xl:table-cell px-6 py-4 text-center text-sm text-slate-600 dark:text-slate-300">
                                <?= $criado ?: '—' ?>
                            </td>
                            <td class="hidden sm:table-cell px-4 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <?php
                                    $action = 'view';
                                    $href = "/admin/usuarios/{$u->id}";
                                    $size = 'sm';
                                    include __DIR__ . '/../../components/action-button.php';
                                    ?>
                                    <?php
                                    $action = 'edit';
                                    $href = "/admin/usuarios/{$u->id}/editar";
                                    $size = 'sm';
                                    include __DIR__ . '/../../components/action-button.php';
                                    ?>
                                    <form action="/admin/usuarios/<?= $u->id ?>/toggle" method="POST" class="inline">
                                        <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
                                        <?php $isActive = (int)($u->status ?? 0) === 1; ?>
                                        <button type="submit" title="<?= $isActive ? 'Bloquear' : 'Liberar' ?>" class="inline-flex items-center justify-center rounded-full h-8 w-8 border border-transparent shadow-sm transition <?= $isActive ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50' : 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-300 hover:bg-rose-100 dark:hover:bg-rose-900/50' ?>">
                                            <i data-lucide="<?= $isActive ? 'unlock' : 'lock' ?>" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-3">
        <?php foreach ($usuarios as $u): ?>
            <?php
                $nomeCompleto = trim(Security::sanitizeString(($u->nome ?? '') . ' ' . ($u->sobrenome ?? '')));
                $email = Security::sanitizeString($u->email ?? '');
                $role = Security::sanitizeString($u->role ?? '');
                $status = (int) ($u->status ?? 0);
                $whats = Security::sanitizeString($u->whatsapp ?? '');
                $criado = '';
                if ($u->created_at ?? null) {
                    try {
                        $dt = new DateTime($u->created_at);
                        $criado = $dt->format('d/m/Y \á\s H:i');
                    } catch (Exception $e) {
                        $criado = Security::sanitizeString($u->created_at);
                    }
                }
                $avatarFallback = '/uploads/fallback-images/fallback-avatar.webp';
                $avatar = $u->imagem_perfil
                    ? Security::sanitizeString($u->imagem_perfil)
                    : $avatarFallback;
                
                $user = $u;
                include __DIR__ . '/../../components/user-card.php';
            ?>
        <?php endforeach; ?>
    </div>
</section>
<script>
(function() {
    const rows = Array.from(document.querySelectorAll('tr[data-name]'));
    const cards = Array.from(document.querySelectorAll('.sm\\:hidden [data-name]'));
    const txt = document.getElementById('filtro-texto');
    const role = document.getElementById('filtro-role');
    const status = document.getElementById('filtro-status');

    function applyFilter() {
        const q = (txt?.value || '').toLowerCase();
        const r = role?.value || '';
        const s = status?.value ?? '';
        
        rows.forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const whats = row.dataset.whats || '';
            const roleVal = row.dataset.role || '';
            const statusVal = row.dataset.status || '';
            const matchText = name.includes(q) || email.includes(q) || whats.includes(q);
            const matchRole = !r || roleVal === r;
            const matchStatus = s === '' || statusVal === s;
            row.style.display = (matchText && matchRole && matchStatus) ? '' : 'none';
        });

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const email = card.dataset.email || '';
            const whats = card.dataset.whats || '';
            const roleVal = card.dataset.role || '';
            const statusVal = card.dataset.status || '';
            const matchText = name.includes(q) || email.includes(q) || whats.includes(q);
            const matchRole = !r || roleVal === r;
            const matchStatus = s === '' || statusVal === s;
            card.style.display = (matchText && matchRole && matchStatus) ? '' : 'none';
        });
    }

    // custom select
    const triggers = document.querySelectorAll('[data-select-trigger]');
    triggers.forEach(trigger => {
        const key = trigger.dataset.selectTrigger;
        const menu = document.querySelector(`[data-select-menu="${key}"]`);
        const labelEl = trigger.querySelector(`[data-select-label="${key}"]`);
        const select = document.getElementById(key);

        const closeMenus = () => {
            document.querySelectorAll('[data-select-menu]').forEach(m => m.classList.add('hidden'));
        };

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = menu?.classList.contains('hidden');
            closeMenus();
            if (isHidden) menu?.classList.remove('hidden');
        });

        menu?.querySelectorAll('[data-select-option]').forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const val = option.dataset.value ?? '';
                const text = option.textContent.trim();
                if (select) select.value = val;
                if (labelEl) labelEl.textContent = text;
                menu.classList.add('hidden');
                applyFilter();
            });
        });

        document.addEventListener('click', closeMenus);
    });

    ['input'].forEach(ev => {
        txt?.addEventListener(ev, applyFilter);
    });
    applyFilter();
})();
</script>
