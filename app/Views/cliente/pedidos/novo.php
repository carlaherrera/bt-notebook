<?php
// /app/Views/cliente/pedidos/novo.php
?>

<section class="space-y-6">
    <header class="relative overflow-hidden rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 px-5 py-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 rounded-full border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/70 px-3 py-1 text-xs font-semibold text-stone-700 dark:text-stone-200">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    Novo pedido
                </div>
                <h1 class="text-3xl font-bold text-stone-900 dark:text-white">Montar pedido</h1>
                <p class="text-sm text-stone-600 dark:text-stone-300">Escolha itens, endereço e pagamento.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="/cliente" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar ao painel
                </a>
                <a href="/cliente/pedidos" class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 px-4 py-2 text-sm font-semibold text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800 transition">
                    <i data-lucide="list" class="w-4 h-4"></i>
                    Ver pedidos
                </a>
                <button class="inline-flex items-center gap-2 rounded-xl bg-stone-900 text-white px-4 py-2 text-sm font-semibold hover:bg-stone-800 transition">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar rascunho
                </button>
            </div>
        </div>
    </header>

    <form action="/cliente/pedidos" method="POST" class="grid gap-4 grid-cols-1 xl:grid-cols-3">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Security::csrfToken(); ?>">
        <div class="xl:col-span-2 space-y-4">
            <!-- Produtos -->
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Itens do pedido</h3>
                    <button type="button" class="text-xs text-stone-600 dark:text-stone-300 hover:underline" data-clear-itens>Limpar</button>
                </div>
                <div class="space-y-3">
                    <?php foreach ($produtos as $produto): $pid = (int)($produto['id'] ?? 0); ?>
                        <div class="p-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 flex items-center justify-between gap-3" data-item data-price="<?= htmlspecialchars((float)($produto['preco'] ?? 0), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                            <div>
                                <p class="text-sm font-semibold text-stone-900 dark:text-white"><?= htmlspecialchars($produto['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                                <p class="text-xs text-stone-600 dark:text-stone-300">R$ <?= htmlspecialchars(number_format((float)($produto['preco'] ?? 0), 2, ',', '.'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                            </div>
                            <div class="flex items-center gap-2 text-sm" data-qty-wrapper>
                                <input type="hidden" name="itens[<?= $pid ?>][produto_id]" value="<?= $pid ?>">
                                <button type="button" class="px-2 py-1 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800" data-qty-btn="dec">-</button>
                                <input type="number" name="itens[<?= $pid ?>][qtd]" value="0" min="0" class="w-14 text-center rounded-lg border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 py-1 text-sm" data-qty-input>
                                <button type="button" class="px-2 py-1 rounded-lg border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-100 hover:bg-stone-100 dark:hover:bg-stone-800" data-qty-btn="inc">+</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Endereço e entrega -->
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-3">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Entrega</h3>
                <div class="grid gap-3 md:grid-cols-2">
                    <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                        <span class="font-semibold">Endereço</span>
                        <select name="endereco_id" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($enderecos as $end): 
                                $label = trim(($end['titulo'] ?? 'Sem título') . ' · ' . ($end['linha1'] ?? '')); ?>
                                <option value="<?= (int)($end['id'] ?? 0) ?>"><?= htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <label class="space-y-1 text-sm text-stone-700 dark:text-stone-200">
                    <span class="font-semibold">Observações</span>
                    <textarea name="observacoes" rows="3" class="w-full rounded-xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 px-3 py-2 text-sm" placeholder="Ex: deixar na portaria"></textarea>
                </label>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Resumo -->
            <div class="p-4 rounded-2xl bg-white dark:bg-stone-900 border border-stone-200 dark:border-stone-800 shadow-sm space-y-2">
                <h3 class="text-sm font-semibold text-stone-900 dark:text-white">Resumo</h3>
                <p class="text-xs text-stone-600 dark:text-stone-300">Itens selecionados</p>
                <div class="text-xs text-stone-600 dark:text-stone-300 space-y-1" data-resumo-itens>
                    <div class="flex justify-between"><span>Nenhum item</span><span>0</span></div>
                </div>
                <div class="text-xs text-stone-600 dark:text-stone-300 space-y-1">
                    <div class="flex justify-between"><span>Itens</span><span data-count-itens>0</span></div>
                    <div class="flex justify-between"><span>Entrega</span><span data-frete-mini>R$ 0,00</span></div>
                    <div class="flex justify-between font-semibold text-stone-900 dark:text-white"><span>Total</span><span data-total-mini>R$ 0,00</span></div>
                </div>
                <div class="p-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-stone-800/40 text-sm text-stone-700 dark:text-stone-200">
                    <div class="flex items-center justify-between">
                        <span>Subtotal</span>
                        <span data-subtotal>R$ 0,00</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Entrega</span>
                        <span data-frete>R$ 0,00</span>
                    </div>
                    <div class="flex items-center justify-between font-semibold text-stone-900 dark:text-white mt-2">
                        <span>Total</span>
                        <span data-total>R$ 0,00</span>
                    </div>
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-stone-900 text-white text-sm font-semibold hover:bg-stone-800">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Confirmar pedido
                </button>
            </div>
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const freteBase = 15;
    const fmt = (v) => 'R$ ' + Number(v).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const subtotalEl = document.querySelector('[data-subtotal]');
    const totalEl = document.querySelector('[data-total]');
    const totalMini = document.querySelector('[data-total-mini]');
    const freteMini = document.querySelector('[data-frete-mini]');
    const countItensEl = document.querySelector('[data-count-itens]');
    const resumoItens = document.querySelector('[data-resumo-itens]');

    const atualizarResumo = () => {
        let subtotal = 0;
        let count = 0;
        const linhas = [];
        document.querySelectorAll('[data-item]').forEach(item => {
            const price = Number(item.getAttribute('data-price') || 0);
            const input = item.querySelector('[data-qty-input]');
            const qtd = Number(input?.value || 0);
            if (qtd > 0) {
                const nome = item.querySelector('p.text-sm')?.textContent?.trim() || 'Produto';
                subtotal += price * qtd;
                count += qtd;
                linhas.push(`<div class="flex justify-between"><span>${nome} x${qtd}</span><span>${fmt(price * qtd)}</span></div>`);
            }
        });

        const frete = subtotal > 0 ? freteBase : 0;

        if (subtotalEl) subtotalEl.textContent = fmt(subtotal);
        if (totalEl) totalEl.textContent = fmt(subtotal + frete);
        if (totalMini) totalMini.textContent = fmt(subtotal + frete);
        if (freteMini) freteMini.textContent = fmt(frete);
        if (countItensEl) countItensEl.textContent = count.toString();
        if (resumoItens) {
            resumoItens.innerHTML = linhas.length ? linhas.join('') : '<div class="flex justify-between"><span>Nenhum item</span><span>0</span></div>';
        }
    };

    document.querySelectorAll('[data-qty-wrapper]').forEach(wrapper => {
        const input = wrapper.querySelector('[data-qty-input]');
        const btnInc = wrapper.querySelector('[data-qty-btn="inc"]');
        const btnDec = wrapper.querySelector('[data-qty-btn="dec"]');
        if (!input || !btnInc || !btnDec) return;

        const setVal = (val) => {
            const min = Number(input.getAttribute('min')) || 0;
            const newVal = Math.max(min, val);
            input.value = newVal;
            atualizarResumo();
        };

        btnInc.addEventListener('click', () => {
            setVal(Number(input.value || 0) + 1);
        });

        btnDec.addEventListener('click', () => {
            setVal(Number(input.value || 0) - 1);
        });

        input.addEventListener('change', () => {
            setVal(Number(input.value || 0));
        });
    });

    const btnClear = document.querySelector('[data-clear-itens]');
    if (btnClear) {
        btnClear.addEventListener('click', () => {
            document.querySelectorAll('[data-qty-input]').forEach(inp => inp.value = 0);
            atualizarResumo();
        });
    }

    // inicializa
    atualizarResumo();
});
</script>
