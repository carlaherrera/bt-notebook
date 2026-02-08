<?php
/**
 * Media Upload Component
 * @var string $label - Label do campo
 * @var string $name - Nome do input
 * @var string $currentValue - Valor/caminho atual (opcional)
 * @var string $preview - URL da imagem preview (opcional)
 * @var string $accept - Tipos de arquivo aceitos (default: image/*)
 * @var string $maxSize - Tamanho máximo em MB (default: 5)
 * @var bool $required - Campo obrigatório (default: false)
 */
$label = $label ?? 'Arquivo';
$name = $name ?? 'media';
$currentValue = $currentValue ?? '';
$preview = $preview ?? '';
$accept = $accept ?? 'image/png, image/jpeg, image/webp, image/svg+xml';
$maxSize = $maxSize ?? '5';
$required = $required ?? false;
$inputId = 'media-' . uniqid();
$fileNameId = 'filename-' . uniqid();
$previewId = 'preview-' . uniqid();
$removeInputId = 'remove-' . uniqid();
?>

<div class="space-y-3">
    <div class="flex items-center justify-between">
        <label class="text-sm font-semibold text-slate-600 dark:text-slate-300"><?= $label ?></label>
        <button type="button" class="text-xs font-semibold text-rose-500 hover:text-rose-600" onclick="
            (function() {
                const input = document.getElementById('<?= $inputId ?>');
                const fileNameEl = document.getElementById('<?= $fileNameId ?>');
                const previewEl = document.getElementById('<?= $previewId ?>');
                const icon = previewEl.parentElement.querySelector('i');
                if (input) {
                    input.value = '';
                }
                if (fileNameEl) {
                    fileNameEl.innerText = 'Nenhum arquivo';
                }
                if (previewEl) {
                    previewEl.removeAttribute('src');
                    previewEl.style.display = 'none';
                }
                if (icon) {
                    icon.style.display = '';
                }
                const removeInput = document.getElementById('<?= $removeInputId ?>');
                if (removeInput) {
                    removeInput.value = '1';
                }
            })();
        ">Remover</button>
    </div>
    <div class="flex flex-col gap-4 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/70 p-4 md:flex-row md:items-center">
        <div class="relative h-24 w-24 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-center flex-shrink-0">
            <img id="<?= $previewId ?>" src="<?= $preview ?: 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22currentColor%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3E%3Crect width=%2218%22 height=%2218%22 x=%223%22 y=%223%22 rx=%222%22 ry=%222%22/%3E%3Ccircle cx=%228.5%22 cy=%228.5%22 r=%221.5%22/%3E%3Cpath d=%22m21 15-5-5L5 21%22/%3E%3C/svg%3E' ?>" alt="Preview" class="absolute inset-0 h-full w-full object-cover text-slate-400" style="<?= $preview ? '' : 'display: none;' ?>">
            <i data-lucide="image" class="h-6 w-6 text-slate-400 dark:text-slate-500" style="<?= $preview ? 'display: none;' : '' ?>"></i>
        </div>
        <div class="flex-1 space-y-3">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Arraste ou selecione um arquivo</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Formatos PNG, JPG ou WEBP com até <?= $maxSize ?>MB.</p>
            <div class="flex flex-wrap items-center gap-3">
                <label for="<?= $inputId ?>" class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold shadow-sm bg-blue-600 text-white hover:bg-blue-700 cursor-pointer transition">
                    <i data-lucide="upload" class="h-4 w-4"></i>
                    Selecionar arquivo
                </label>
                <span id="<?= $fileNameId ?>" class="text-xs text-slate-500 dark:text-slate-300 truncate max-w-[220px]">
                    <?= $currentValue ? basename($currentValue) : 'Nenhum arquivo' ?>
                </span>
            </div>
        </div>
        <input type="file" name="<?= $name ?>" accept="<?= $accept ?>" class="hidden" id="<?= $inputId ?>" onchange="
            const file = this.files[0];
            const fileNameEl = document.getElementById('<?= $fileNameId ?>');
            const preview = document.getElementById('<?= $previewId ?>');
            const icon = preview?.parentElement?.querySelector('i');
            document.getElementById('<?= $removeInputId ?>').value = file ? '0' : document.getElementById('<?= $removeInputId ?>').value;
            if (fileNameEl) {
                fileNameEl.innerText = file?.name ?? 'Nenhum arquivo';
            }
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    if (icon) {
                        icon.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        " <?= $required ? 'required' : '' ?>>
        <input type="hidden" name="<?= $name ?>_current" value="<?= \App\Core\Security::sanitizeString($currentValue) ?>">
        <input type="hidden" name="<?= $name ?>_remove" id="<?= $removeInputId ?>" value="0">
    </div>
</div>
