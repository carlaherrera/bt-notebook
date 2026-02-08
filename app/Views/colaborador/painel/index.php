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
            <p class="text-sm text-slate-500 dark:text-slate-400">Visão geral e métricas do time.</p>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-6">
        <div class="p-3 sm:p-5 bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Projetos ativos</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">18</p>
                </div>
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl flex items-center justify-center"
                    style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="briefcase" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i> 3
                </span>
                <span class="text-gray-500 dark:text-gray-400">vs. semana passada</span>
            </div>
        </div>

        <div class="p-3 sm:p-5 bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tempo médio de resposta</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">42min</p>
                </div>
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl flex items-center justify-center"
                    style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="clock" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                    <i data-lucide="arrow-down-left" class="w-4 h-4"></i> -8%
                </span>
                <span class="text-gray-500 dark:text-gray-400">últimas 24h</span>
            </div>
        </div>

        <div class="p-3 sm:p-5 bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">SLA cumprido</p>
                    <p class="text-xl sm:text-2xl font-semibold text-gray-900 dark:text-white">94%</p>
                </div>
                <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl flex items-center justify-center"
                    style="background: var(--primary-50); color: var(--primary-700);">
                    <i data-lucide="shield-check" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-500 dark:text-gray-400">Últimos 30 dias</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Projetos em andamento</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Prioridade e prazos</p>
                </div>
                <button class="px-3 py-2 text-sm rounded-lg btn-primary transition">Ver todos</button>
            </div>

            <div class="space-y-3">
                <?php
                $projetos = [
                    ['id' => '#P-4821', 'titulo' => 'Onboarding de novo cliente', 'prioridade' => 'Alta', 'prazo' => 'Hoje, 18h', 'tag' => 'Sucesso do Cliente', 'status' => 'Em andamento'],
                    ['id' => '#P-4819', 'titulo' => 'Revisão de conteúdo do site', 'prioridade' => 'Média', 'prazo' => 'Amanhã, 12h', 'tag' => 'Conteúdo', 'status' => 'Planejamento'],
                    ['id' => '#P-4816', 'titulo' => 'Integração com gateway', 'prioridade' => 'Baixa', 'prazo' => 'Próx. semana', 'tag' => 'Integração', 'status' => 'Execução'],
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
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">Tag: <?= $projeto['tag'] ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Fila pessoal</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tarefas atribuídas</p>
                </div>
                <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i data-lucide="refresh-cw" class="w-4 h-4 text-gray-500 dark:text-gray-400"></i>
                </button>
            </div>

            <div class="space-y-3">
                <?php
                $tarefas = [
                    ['titulo' => 'Responder cliente ACME', 'status' => 'Em andamento'],
                    ['titulo' => 'Revisar FAQ de suporte', 'status' => 'Pendente'],
                    ['titulo' => 'Atualizar macro de SLA', 'status' => 'Em andamento'],
                ];
                ?>
                <?php foreach ($tarefas as $tarefa): ?>
                    <div class="p-3 rounded-xl border border-gray-100 dark:border-gray-700 flex flex-col gap-2">
                        <div class="flex items-start gap-3">
                            <span class="w-2.5 h-2.5 rounded-full mt-1
                                <?= $tarefa['status'] === 'Em andamento' ? 'bg-amber-500' : 'bg-gray-400' ?>"></span>
                            <p class="text-sm text-gray-900 dark:text-white leading-snug"><?= $tarefa['titulo'] ?></p>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 inline-flex w-fit">
                            <?= $tarefa['status'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</section>
