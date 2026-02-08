<?php
// /app/Views/layouts/painel.php
// Layout para painéis (Admin, Colaborador, Cliente)
// Estende o layout base com sidebar, header e footer

if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}

$title = isset($title) ? $title . ' · ' : '';
$title .= 'Painel';
$pageContent = $content;
$content = '';

ob_start();
?>
<div class="flex h-screen">

    <!-- Sidebar Dinâmico -->
    <?php require VIEW_PATH . '/components/sidebar.php'; ?>

    <!-- Conteúdo Principal -->
    <div class="flex flex-col flex-1">

        <!-- Header Dinâmico -->
        <?php require VIEW_PATH . '/components/header.php'; ?>

        <!-- Página -->
        <main class="flex-1 overflow-y-auto p-6">
            <?php require VIEW_PATH . '/components/flash.php'; ?>
            <?= $pageContent ?>
        </main>

        <!-- Footer Dinâmico -->
        <?php require VIEW_PATH . '/components/footer.php'; ?>

    </div>
</div>
<?php
$content = ob_get_clean();

// Renderizar com o layout base
require VIEW_PATH . '/layouts/base.php';
