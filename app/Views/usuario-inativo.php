<?php
/**
 * @var string $whatsapp
 */
$title = 'Usuário Inativo';
$waLink = !empty($whatsapp) ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsapp) : '#';
?>

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/20 border border-red-500/30">
                <i data-lucide="lock" class="w-8 h-8 text-red-500"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">Acesso Bloqueado</h1>
            <p class="text-slate-400">Sua conta foi desativada no sistema.</p>
        </div>

        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 space-y-4">
            <div class="space-y-2">
                <p class="text-sm text-slate-300">
                    Sua conta foi bloqueada ou desativada. Se você acredita que isso é um erro, entre em contato conosco pelo WhatsApp.
                </p>
            </div>

            <?php if (!empty($whatsapp)): ?>
                <a href="<?php echo htmlspecialchars($waLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 transition">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    Contatar via WhatsApp
                </a>
            <?php endif; ?>

            <a href="/sair" class="inline-flex items-center justify-center gap-2 w-full rounded-xl border border-slate-600 hover:border-slate-500 text-slate-300 hover:text-white font-semibold py-3 px-4 transition">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Sair
            </a>
        </div>

        <p class="text-xs text-slate-500">
            Se você continuar tendo problemas, tente fazer login novamente ou limpe o cache do navegador.
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
