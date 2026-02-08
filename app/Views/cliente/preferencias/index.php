<?php
use App\Core\Security;
/** @var string $theme */
$title = 'Preferências';
$theme = $theme ?? 'system';
?>

<section class="space-y-6">
    <header class="flex flex-col gap-1">
        <p class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-[0.18em]">Cliente</p>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Preferências</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Escolha como o tema deve se comportar.</p>
    </header>

    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-5">
        <form action="/cliente/preferencias" method="POST" class="space-y-4">
            <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Tema</p>
                <div class="grid gap-3 sm:grid-cols-3">
                    <?php
                    $options = [
                        ['value' => 'system', 'label' => 'Seguir sistema', 'icon' => 'monitor'],
                        ['value' => 'light', 'label' => 'Claro', 'icon' => 'sun'],
                        ['value' => 'dark', 'label' => 'Escuro', 'icon' => 'moon']
                    ];
                    foreach ($options as $opt):
                        $checked = $theme === $opt['value'] ? 'checked' : '';
                    ?>
                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 shadow-sm cursor-pointer transition hover:border-[color-mix(in_srgb,var(--primary-color)_40%,white)] dark:hover:border-[color-mix(in_srgb,var(--primary-color)_40%,transparent)]">
                        <input type="radio" name="theme_preference" value="<?= $opt['value'] ?>" class="h-4 w-4 text-[color-mix(in_srgb,var(--primary-color)_70%,white_30%)] focus:ring-[color-mix(in_srgb,var(--primary-color)_70%,white_30%)]" <?= $checked ?>>
                        <span class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-100">
                            <i data-lucide="<?= $opt['icon'] ?>" class="h-4 w-4"></i>
                            <?= $opt['label'] ?>
                        </span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold shadow-sm transition" style="background: var(--primary-color); color: #fff;" onmouseover="this.style.background='var(--primary-color-hover)';" onmouseout="this.style.background='var(--primary-color)';">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Salvar
                </button>
            </div>
        </form>
    </section>
</section>
