<?php
use App\Core\Security;
/**
 * @var array $settings
 * @var string $themePref
 */
$title = 'Configurações';

$org = array_merge([
    'org_name' => '',
    'slogan' => '',
    'cep' => '',
    'rua' => '',
    'numero' => '',
    'cidade' => '',
    'estado' => '',
    'telefone' => '',
    'whatsapp' => '',
    'email' => '',
    'cnpj' => '',
    'logo_light_path' => '',
    'logo_dark_path' => '',
    'favicon_path' => '',
    'primary_color' => '',
    'secondary_color' => '',
], $settings ?? []);
?>

<style>
    :root {
        --primary-50: color-mix(in srgb, var(--primary-color) 12%, white);
        --primary-100: color-mix(in srgb, var(--primary-color) 20%, white);
        --primary-200: color-mix(in srgb, var(--primary-color) 32%, white);
        --primary-500: var(--primary-color);
        --primary-600: var(--primary-color-hover);
        --primary-700: color-mix(in srgb, var(--primary-color) 85%, black 15%);
    }
    form input, form textarea, form select {
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    form input:focus, form textarea:focus, form select:focus {
        outline: none;
        border-color: var(--primary-color) !important;
        box-shadow: inset 0 0 0 1px var(--primary-color) !important;
    }
</style>

<section class="space-y-6">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Configurações</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Tema padrão, dados da organização e personalização do sistema.</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-2xl px-3 py-2 text-xs font-medium"
            style="background: var(--primary-50); color: var(--primary-700); border: 1px solid var(--primary-100);">
            <i data-lucide="sparkles" class="h-4 w-4" style="color: var(--primary-600);"></i>
            Preparado para futuras personalizações
        </div>
    </header>

    <form action="/admin/configuracoes" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

        <!-- Tema -->
        <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="moon-star" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Tema padrão</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Defina o tema preferido (aplica para admins e demais usuários).</p>
                </div>
            </div>
            <div class="grid gap-3 grid-cols-1 sm:grid-cols-3">
                <?php
                $themes = [
                    ['value' => 'system', 'label' => 'Seguir sistema', 'icon' => 'monitor'],
                    ['value' => 'light', 'label' => 'Claro', 'icon' => 'sun'],
                    ['value' => 'dark', 'label' => 'Escuro', 'icon' => 'moon'],
                ];
                foreach ($themes as $th):
                    $checked = $themePref === $th['value'] ? 'checked' : '';
                ?>
                <label class="flex items-center gap-3 p-3 border border-slate-300 rounded-lg bg-white dark:bg-slate-800 dark:border-slate-600 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700">
                    <input type="radio" name="theme_preference" value="<?= $th['value'] ?>" class="w-4 h-4" <?= $checked ?>>
                    <div class="flex items-center gap-2">
                        <i data-lucide="<?= $th['icon'] ?>" class="w-4 h-4 text-slate-600 dark:text-slate-400"></i>
                        <span class="text-sm font-medium text-slate-900 dark:text-slate-100"><?= $th['label'] ?></span>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Organização -->
        <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-8">

            <!-- Dados da empresa -->
            <div class="space-y-4">
                <div class="space-y-2">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white" style="color: var(--primary-color);">Dados da empresa</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Nome, slogan e CNPJ exibidos em títulos e comunicações.</p>
                    </div>
                    <div class="w-full border-b border-slate-200 dark:border-slate-700"></div>
                </div>

                <div class="grid gap-4 org-grid-empresa">
                    <style scoped>
                        .org-grid-empresa { grid-template-columns: repeat(1, minmax(0, 1fr)); }
                        @media (min-width: 768px) {
                            .org-grid-empresa { grid-template-columns: repeat(3, minmax(0, 1fr)); }
                        }
                        @media (min-width: 1024px) {
                            .org-grid-empresa { grid-template-columns: 35fr 35fr 30fr; }
                        }
                    </style>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="org_name">Nome da empresa</label>
                        <input id="org_name" name="org_name" value="<?= Security::sanitizeString($org['org_name']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="slogan">Slogan</label>
                        <input id="slogan" name="slogan" value="<?= Security::sanitizeString($org['slogan']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="Ex.: Plataforma de Gestão Inteligente">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="cnpj">CNPJ (opcional)</label>
                        <input id="cnpj" name="cnpj" value="<?= Security::sanitizeString($org['cnpj']) ?>" class="w-full rounded-xl border border-slate-200 dark-border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="space-y-4">
                <div class="space-y-2">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white" style="color: var(--primary-color);">Endereço</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Informe o CEP para autocompletar rua, cidade e estado.</p>
                    </div>
                    <div class="w-full border-b border-slate-200 dark:border-slate-700"></div>
                </div>

                <div class="grid gap-4 md:grid-cols-[160px,1fr,1fr]">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="cep">CEP</label>
                        <input id="cep" name="cep" value="<?= Security::sanitizeString($org['cep']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" placeholder="00000-000" onblur="buscarCEP(this.value)">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="rua">Rua</label>
                        <input id="rua" name="rua" value="<?= Security::sanitizeString($org['rua']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="numero">Número</label>
                        <input id="numero" name="numero" value="<?= Security::sanitizeString($org['numero']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="cidade">Cidade</label>
                        <input id="cidade" name="cidade" value="<?= Security::sanitizeString($org['cidade']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="estado">Estado (UF)</label>
                        <input id="estado" name="estado" value="<?= Security::sanitizeString($org['estado']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" maxlength="2">
                    </div>
                </div>
            </div>

            <!-- Contatos -->
            <div class="space-y-4">
                <div class="space-y-2">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white" style="color: var(--primary-color);">Contatos</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Canais para clientes e notificações.</p>
                    </div>
                    <div class="w-full border-b border-slate-200 dark:border-slate-700"></div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="email">Email</label>
                        <input id="email" name="email" value="<?= Security::sanitizeString($org['email']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="telefone">Telefone</label>
                        <input id="telefone" name="telefone" value="<?= Security::sanitizeString($org['telefone']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="whatsapp">WhatsApp</label>
                        <input id="whatsapp" name="whatsapp" value="<?= Security::sanitizeString($org['whatsapp']) ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50">
                    </div>
                </div>
            </div>

        </section>

        <!-- Personalização -->
        <section class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4 sm:p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl flex items-center justify-center" style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="palette" class="h-5 w-5"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Personalização</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Logos, favicon e cores do sistema.</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <?php
                $label = 'Logo (light)';
                $name = 'logo_light_path';
                $currentValue = $org['logo_light_path'];
                $preview = $org['logo_light_path'] ?: '';
                include __DIR__ . '/../../components/media-upload.php';
                ?>
                <?php
                $label = 'Logo (dark)';
                $name = 'logo_dark_path';
                $currentValue = $org['logo_dark_path'];
                $preview = $org['logo_dark_path'] ?: '';
                include __DIR__ . '/../../components/media-upload.php';
                ?>
                <?php
                $label = 'Favicon';
                $name = 'favicon_path';
                $currentValue = $org['favicon_path'];
                $preview = $org['favicon_path'] ?: '';
                include __DIR__ . '/../../components/media-upload.php';
                ?>
            </div>

            <div class="grid gap-4 md:grid-cols-1">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="primary_color">Cor primária</label>
                    <div class="flex items-center gap-3">
                        <input id="primary_color" name="primary_color" type="color" value="<?= Security::sanitizeString($org['primary_color'] ?: '#2563eb') ?>" class="w-16 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer">
                        <input type="text" class="flex-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" id="primary_color_text" value="<?= Security::sanitizeString($org['primary_color'] ?: '#2563eb') ?>" onchange="document.getElementById('primary_color').value = this.value;" oninput="document.getElementById('primary_color').value = this.value;">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600 dark:text-slate-300" for="secondary_color">Cor secundária</label>
                    <div class="flex items-center gap-3">
                        <input id="secondary_color" name="secondary_color" type="color" value="<?= Security::sanitizeString($org['secondary_color'] ?: '#db0000') ?>" class="w-16 h-10 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer">
                        <input type="text" class="flex-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-50" id="secondary_color_text" value="<?= Security::sanitizeString($org['secondary_color'] ?: '#db0000') ?>" onchange="document.getElementById('secondary_color').value = this.value;" oninput="document.getElementById('secondary_color').value = this.value;">
                    </div>
                </div>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl text-white px-4 py-2 text-sm font-semibold shadow-sm transition"
                style="background: var(--primary-color);"
                onmouseover="this.style.background='var(--primary-color-hover)';"
                onmouseout="this.style.background='var(--primary-color)';">
                <i data-lucide="save" class="h-4 w-4"></i>
                Salvar configurações
            </button>
        </div>
    </form>
</section>

<script>
async function buscarCEP(cep) {
    const clean = (cep || '').replace(/\D/g, '');
    if (clean.length !== 8) return;

    try {
        const res = await fetch(`/admin/configuracoes/cep/${clean}`, { credentials: 'include' });
        if (!res.ok) {
            console.warn('CEP lookup falhou (HTTP)', res.status);
            return;
        }
        const data = await res.json();
        if (!data || data.error) {
            console.warn('CEP não encontrado', data);
            return;
        }
        const rua = document.getElementById('rua');
        const cidade = document.getElementById('cidade');
        const estado = document.getElementById('estado');
        if (rua && data.logradouro) rua.value = data.logradouro;
        if (cidade && data.localidade) cidade.value = data.localidade;
        if (estado && data.uf) estado.value = data.uf.toString().slice(0, 2);
    } catch (err) {
        console.error('Erro ao buscar CEP', err);
    }
}

function maskPhone(value) {
    const digits = (value || '').replace(/\D/g, '').slice(0, 10);
    const match = digits.match(/^(\d{2})(\d{0,4})(\d{0,4})$/);
    if (!match) return digits;
    const [, ddd, part1, part2] = match;
    if (part2) return `(${ddd}) ${part1}-${part2}`;
    if (part1) return `(${ddd}) ${part1}`;
    return `(${ddd}`;
}

function maskWhats(value) {
    const digits = (value || '').replace(/\D/g, '').slice(0, 11);
    const match = digits.match(/^(\d{2})(\d{0,5})(\d{0,4})$/);
    if (!match) return digits;
    const [, ddd, part1, part2] = match;
    if (part2) return `(${ddd}) ${part1}-${part2}`;
    if (part1) return `(${ddd}) ${part1}`;
    return `(${ddd}`;
}

function maskCep(value) {
    const digits = (value || '').replace(/\D/g, '').slice(0, 8);
    const match = digits.match(/^(\d{0,5})(\d{0,3})$/);
    if (!match) return digits;
    const [, part1, part2] = match;
    if (part2) return `${part1}-${part2}`;
    return part1;
}

function maskCnpj(value) {
    const digits = (value || '').replace(/\D/g, '').slice(0, 14);
    const match = digits.match(/^(\d{2})(\d{0,3})(\d{0,3})(\d{0,4})(\d{0,2})$/);
    if (!match) return digits;
    const [, p1, p2, p3, p4, p5] = match;
    if (p5) return `${p1}.${p2}.${p3}/${p4}-${p5}`;
    if (p4) return `${p1}.${p2}.${p3}/${p4}`;
    if (p3) return `${p1}.${p2}.${p3}`;
    if (p2) return `${p1}.${p2}`;
    return p1;
}

document.addEventListener('DOMContentLoaded', () => {
    const tel = document.getElementById('telefone');
    const wap = document.getElementById('whatsapp');
    const cep = document.getElementById('cep');
    const cnpj = document.getElementById('cnpj');
    if (tel) {
        tel.addEventListener('input', (e) => {
            e.target.value = maskPhone(e.target.value);
        });
        tel.value = maskPhone(tel.value);
    }
    if (wap) {
        wap.addEventListener('input', (e) => {
            e.target.value = maskWhats(e.target.value);
        });
        wap.value = maskWhats(wap.value);
    }
    if (cnpj) {
        cnpj.addEventListener('input', (e) => {
            e.target.value = maskCnpj(e.target.value);
        });
        cnpj.value = maskCnpj(cnpj.value);
    }
    if (cep) {
        const triggerCep = (value) => {
            const clean = (value || '').replace(/\D/g, '');
            if (clean.length === 8) {
                buscarCEP(clean);
            }
        };
        cep.addEventListener('input', (e) => {
            const masked = maskCep(e.target.value);
            e.target.value = masked;
            triggerCep(masked);
        });
        cep.addEventListener('blur', (e) => triggerCep(e.target.value));
        cep.value = maskCep(cep.value);
        triggerCep(cep.value);
    }
});
</script>
