<?php

use App\Core\Flash;

$flash = Flash::get();

if ($flash): ?>
<div class="mb-4 px-4 py-3 rounded-lg border text-sm font-medium
    <?= $flash['type'] === 'erro' ? 'bg-red-50 border-red-200 text-red-800' : '' ?>
    <?= $flash['type'] === 'sucesso' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : '' ?>
    <?= $flash['type'] === 'info' ? 'bg-blue-50 border-blue-200 text-blue-800' : '' ?>">
    <?= htmlspecialchars($flash['message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
</div>
<?php endif; ?>
