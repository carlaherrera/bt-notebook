<?php
/**
 * Componente de Botão Reutilizável
 * 
 * Uso:
 * <?php include 'components/button.php'; ?>
 * <?= renderButton([
 *     'text' => 'Salvar',
 *     'type' => 'submit',
 *     'variant' => 'primary', // primary, secondary, danger, success, warning
 *     'size' => 'md', // sm, md, lg
 *     'icon' => 'save', // lucide icon name
 *     'href' => null, // se definido, renderiza como <a> ao invés de <button>
 *     'class' => '', // classes adicionais
 *     'disabled' => false,
 * ]) ?>
 */

function renderButton(array $options = []): string {
    $defaults = [
        'text' => 'Botão',
        'type' => 'button',
        'variant' => 'primary',
        'style' => 'solid', // solid, outline
        'size' => 'md',
        'icon' => null,
        'href' => null,
        'class' => '',
        'disabled' => false,
    ];
    
    $opts = array_merge($defaults, $options);
    
    // Definir estilos base por tamanho
    if ($opts['style'] === 'solid') {
        // solid: compacto
        $sizeClasses = match($opts['size']) {
            'sm' => 'px-4 py-0.5 text-xs',
            'lg' => 'px-6 py-1.5 text-base',
            default => 'px-5 py-1 text-sm', // md
        };
    } else {
        // outline: mais altura
        $sizeClasses = match($opts['size']) {
            'sm' => 'px-3 py-1.5 text-xs',
            'lg' => 'px-5 py-2.5 text-base',
            default => 'px-4 py-2 text-sm', // md
        };
    }
    
    // Definir estilos por variante e style
    $variantClasses = match([$opts['variant'], $opts['style']]) {
        // SOLID (filled background)
        ['primary', 'solid'] => 'bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:hover:bg-blue-500 dark:active:bg-blue-600 dark:focus:ring-offset-gray-900',
        ['secondary', 'solid'] => 'bg-stone-600 text-white hover:bg-stone-700 active:bg-stone-800 focus:ring-2 focus:ring-stone-500 focus:ring-offset-2 dark:hover:bg-stone-500 dark:active:bg-stone-600 dark:focus:ring-offset-gray-900',
        ['danger', 'solid'] => 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:hover:bg-red-500 dark:active:bg-red-600 dark:focus:ring-offset-gray-900',
        ['success', 'solid'] => 'bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:hover:bg-green-500 dark:active:bg-green-600 dark:focus:ring-offset-gray-900',
        ['warning', 'solid'] => 'bg-amber-600 text-white hover:bg-amber-700 active:bg-amber-800 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:hover:bg-amber-500 dark:active:bg-amber-600 dark:focus:ring-offset-gray-900',
        
        // OUTLINE (border + transparent background)
        ['primary', 'outline'] => 'border border-blue-600 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:text-blue-400 dark:border-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-950 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
        ['secondary', 'outline'] => 'border border-stone-600 text-stone-600 hover:text-stone-700 hover:bg-stone-50 dark:text-stone-400 dark:border-stone-400 dark:hover:text-stone-300 dark:hover:bg-stone-950 focus:ring-2 focus:ring-stone-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
        ['danger', 'outline'] => 'border border-red-600 text-red-600 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:border-red-400 dark:hover:text-red-300 dark:hover:bg-red-950 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
        ['success', 'outline'] => 'border border-green-600 text-green-600 hover:text-green-700 hover:bg-green-50 dark:text-green-400 dark:border-green-400 dark:hover:text-green-300 dark:hover:bg-green-950 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
        ['warning', 'outline'] => 'border border-amber-600 text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:text-amber-400 dark:border-amber-400 dark:hover:text-amber-300 dark:hover:bg-amber-950 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
        
        default => 'bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:hover:bg-blue-500 dark:active:bg-blue-600 dark:focus:ring-offset-gray-900',
    };
    
    // Classes base
    $baseClasses = 'inline-flex items-center gap-2 rounded-lg font-semibold transition-colors duration-200 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed';
    
    // Adicionar !text-white apenas para botões solid
    if ($opts['style'] === 'solid') {
        $baseClasses .= ' !text-white';
    }
    
    // Combinar classes
    $allClasses = "{$baseClasses} {$sizeClasses} {$variantClasses}";
    if ($opts['class']) {
        $allClasses .= " {$opts['class']}";
    }
    
    // Construir conteúdo do botão
    $content = '';
    if ($opts['icon']) {
        $content .= '<i data-lucide="' . htmlspecialchars($opts['icon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" class="w-4 h-4"></i>';
    }
    $content .= htmlspecialchars($opts['text'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    
    // Renderizar como <a> ou <button>
    if ($opts['href']) {
        return sprintf(
            '<a href="%s" class="%s">%s</a>',
            htmlspecialchars($opts['href'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $allClasses,
            $content
        );
    } else {
        $disabled = $opts['disabled'] ? 'disabled' : '';
        return sprintf(
            '<button type="%s" class="%s" %s>%s</button>',
            htmlspecialchars($opts['type'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $allClasses,
            $disabled,
            $content
        );
    }
}
