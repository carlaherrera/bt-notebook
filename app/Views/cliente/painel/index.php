<style>
    :root {
        --primary-50: color-mix(in srgb, var(--primary-color) 12%, white);
        --primary-100: color-mix(in srgb, var(--primary-color) 20%, white);
        --primary-200: color-mix(in srgb, var(--primary-color) 32%, white);
        --primary-500: var(--primary-color);
        --primary-600: var(--primary-color-hover);
        --primary-700: color-mix(in srgb, var(--primary-color) 85%, black 15%);
    }
    .btn-primary {
        background: var(--primary-500);
        color: #fff;
        transition: background 0.15s ease;
    }
    .btn-primary:hover { background: var(--primary-600); }
</style>

<section class="space-y-6">

    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Painel</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Acompanhe seus projetos e interações.</p>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-6">
        <div class="p-3 sm:p-5 bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Projetos ativos</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">4</p>
                </div>
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl flex items-center justify-center"
                    style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="briefcase" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i> 1
                </span>
                <span class="text-gray-500 dark:text-gray-400">novo nesta semana</span>
            </div>
        </div>

        <div class="p-3 sm:p-5 bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Última resposta</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">1h 12min</p>
                </div>
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl flex items-center justify-center"
                    style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="message-square" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Equipe respondeu rápido hoje</div>
        </div>

        <div class="p-3 sm:p-5 bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Satisfação (CSAT)</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">4.8/5</p>
                </div>
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl flex items-center justify-center"
                    style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="smile" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Baseado nas últimas 10 avaliações</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Meus projetos</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Acompanhe status e prazos</p>
                </div>
                <button class="px-3 py-2 text-sm rounded-lg btn-primary transition">Novo projeto</button>
            </div>

            <div class="space-y-3">
                <?php
                $projetos = [
                    ['id' => '#P-5001', 'titulo' => 'Atualização de layout', 'status' => 'Em andamento', 'prazo' => 'Hoje, 18h', 'prioridade' => 'Alta'],
                    ['id' => '#P-4996', 'titulo' => 'Treinamento de equipe', 'status' => 'Planejamento', 'prazo' => 'Amanhã, 12h', 'prioridade' => 'Média'],
                    ['id' => '#P-4990', 'titulo' => 'Configuração de integrações', 'status' => 'Execução', 'prazo' => 'Próx. semana', 'prioridade' => 'Baixa'],
                ];
                ?>
                <?php foreach ($projetos as $projeto): ?>
                    <?php
                        $statusClass = match($projeto['status']) {
                            'Em andamento' => 'bg-[color-mix(in_srgb,var(--primary-color)_16%,white)] text-[color-mix(in_srgb,var(--primary-color)_80%,black_20%)] dark:bg-[color-mix(in_srgb,var(--primary-color)_18%,transparent)] dark:text-[color-mix(in_srgb,var(--primary-color)_70%,white_30%)]',
                            'Planejamento' => 'bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-200',
                            default => 'bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-200',
                        };
                        $prioClass = match($projeto['prioridade']) {
                            'Alta' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-200',
                            'Média' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-200',
                            default => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200',
                        };
                    ?>
                    <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-2xl hover:border-gray-200 dark:hover:border-gray-600 transition shadow-sm">
                        <div class="flex flex-col gap-3">
                            <!-- Header -->
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3 sm:items-center">
                                    <span class="px-3 py-1 text-[11px] rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 font-semibold tracking-wide shadow-inner/10"><?= $projeto['id'] ?></span>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">Status: <?= $projeto['status'] ?></span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="space-y-1">
                                <p class="font-semibold text-gray-900 dark:text-white leading-snug text-base sm:text-lg"><?= $projeto['titulo'] ?></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 leading-snug flex items-center gap-1">
                                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                                    <?= $projeto['prazo'] ?>
                                </p>
                            </div>

                            <!-- Footer -->
                            <div class="flex flex-wrap items-center gap-2.5">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $prioClass ?>">Prioridade: <?= $projeto['prioridade'] ?></span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">Tag: Cliente</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Atalhos rápidos</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Acesso frequente</p>
                </div>
            </div>

            <div class="space-y-3">
                <a href="/cliente/perfil" class="flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 transition">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg flex items-center justify-center"
                            style="background: var(--primary-50); color: var(--primary-700);">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Dados da conta</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Atualize informações</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>

</section>
