<?php
// /app/Views/cliente/pedidos/editar.php
use App\Core\Security;
?>
<section class="space-y-6">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-stone-900 dark:text-white">Editar pedido #<?= htmlspecialchars($pedido['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
            <p class="text-sm text-stone-600 dark:text-stone-300">Ajuste endereço e itens antes da confirmação.</p>
        </div>
        <div class="flex gap-2">
            <a href="/cliente/pedidos/<?= (int)$pedido['id'] ?>" class="px-4 py-2 rounded-xl border border-stone-200 dark:border-stone-700 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800">Voltar</a>
        </div>
    </header>

    <form action="/cliente/pedidos/<?= (int)$pedido['id'] ?>/editar" method="POST" class="grid gap-6 lg:grid-cols-3" id="pedidoForm">
        <input type="hidden" name="_csrf" value="<?= Security::csrfToken() ?>">

        <div class="lg:col-span-2 space-y-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white mb-3">Endereço de entrega</h3>
                <select name="endereco_id" required class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm">
                    <option value="">Selecione</option>
                    <?php foreach ($enderecos as $end): ?>
                        <option value="<?= (int)$end['id'] ?>" <?= $end['id'] == $pedido['endereco_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($end['titulo'] . ' - ' . $end['linha1'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 space-y-3" id="produtosLista">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Itens</h3>
                    <span class="text-xs text-stone-500">Clique em + para adicionar</span>
                </div>

                <?php foreach (($produtos ?? []) as $produto): ?>
                    <?php
                        $qtdAtual = 0;
                        foreach (($itens ?? []) as $it) {
                            if ((int)$it['produto_id'] === (int)$produto['id']) {
                                $qtdAtual = (int)$it['qtd'];
                                break;
                            }
                        }
                    ?>
                    <div class="flex items-center justify-between gap-3 py-2 border-b border-stone-100 dark:border-stone-800 last:border-0" data-produto-id="<?= (int)$produto['id'] ?>" data-preco="<?= (float)$produto['preco'] ?>">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($produto['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            <p class="text-xs text-stone-500">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 btn-dec">-</button>
                            <input type="number" min="0" name="itens[<?= (int)$produto['id'] ?>][qtd]" value="<?= $qtdAtual ?>" class="w-14 text-center rounded-lg border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 text-sm" />
                            <input type="hidden" name="itens[<?= (int)$produto['id'] ?>][produto_id]" value="<?= (int)$produto['id'] ?>">
                            <button type="button" class="px-3 py-1.5 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hoverbg-stone-800 btn-inc">+</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <aside class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 space-y-4">
            <div>
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Resumo</h3>
                <p class="text-xs text-stone-500">Valores atualizados ao salvar.</p>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-600 dark:text-stone-300">Subtotal</span>
                    <span class="font-semibold text-stone-900 dark:text-white" id="subtotal">R$ 0,00</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-600 dark:text-stone-300">Frete</span>
                    <span class="font-semibold text-stone-900 dark:text-white" id="frete">R$ 0,00</span>
                </div>
                <div class="flex justify-between border-t border-stone-200 dark:border-stone-800 pt-2">
                    <span class="text-stone-700 dark:text-stone-200">Total</span>
                    <span class="text-lg font-bold text-stone-900 dark:text-white" id="total">R$ 0,00</span>
                </div>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800">
                <i data-lucide="save" class="w-4 h-4"></i>
                Salvar alterações
            </button>
        </aside>
    </form>
</section>

<script>
(function(){
    const freteBase = 15;
    const format = (v) => new Intl.NumberFormat('pt-BR', {style:'currency', currency:'BRL'}).format(v);
    const updateResumo = () => {
        let subtotal = 0;
        document.querySelectorAll('#produtosLista [data-produto-id]').forEach(row => {
            const preco = parseFloat(row.getAttribute('data-preco')) || 0;
            const input = row.querySelector('input[type="number"]');
            const qtd = parseInt(input.value || '0', 10);
            if (qtd > 0) subtotal += preco * qtd;
        });
        const frete = subtotal > 0 ? freteBase : 0;
        const subtotalEl = document.getElementById('subtotal');
        const totalEl = document.getElementById('total');
        const freteEl = document.getElementById('frete');
        if (subtotalEl) subtotalEl.textContent = format(subtotal);
        if (freteEl) freteEl.textContent = format(frete);
        if (totalEl) totalEl.textContent = format(subtotal + frete);
    };
    document.querySelectorAll('.btn-inc').forEach(btn => {
        btn.addEventListener('click', () => { const input = btn.parentElement.querySelector('input[type="number"]'); input.value = Math.max(0, (parseInt(input.value||'0',10)+1)); updateResumo(); });
    });
    document.querySelectorAll('.btn-dec').forEach(btn => {
        btn.addEventListener('click', () => { const input = btn.parentElement.querySelector('input[type="number"]'); input.value = Math.max(0, (parseInt(input.value||'0',10)-1)); updateResumo(); });
    });
    document.querySelectorAll('#produtosLista input[type="number"]').forEach(inp => inp.addEventListener('input', updateResumo));
    updateResumo();
})();
</script>
