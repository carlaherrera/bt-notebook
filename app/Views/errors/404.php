<?php
// app/Views/errors/404.php
// Página de erro 404 com visual amigável
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 • Página não encontrada</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: radial-gradient(circle at 20% 20%, #111827, #0b1020 45%, #080b17 70%);
            --card: rgba(17, 24, 39, 0.72);
            --border: rgba(148, 163, 184, 0.25);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --primary: #60a5fa;
            --primary-strong: #1d4ed8;
            --accent: #a855f7;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'Space Grotesk', 'Segoe UI', system-ui, -apple-system, sans-serif;
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }
        .shell {
            width: min(960px, 100%);
            background: linear-gradient(135deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 36px;
            position: relative;
            overflow: hidden;
            box-shadow:
                0 30px 60px rgba(8, 47, 73, 0.35),
                0 0 0 1px rgba(148, 163, 184, 0.12);
        }
        .shell::before, .shell::after {
            content: '';
            position: absolute;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(96,165,250,0.35), transparent 60%);
            filter: blur(12px);
            z-index: 0;
        }
        .shell::before { top: -60px; left: -40px; }
        .shell::after { bottom: -80px; right: -20px; background: radial-gradient(circle, rgba(168,85,247,0.35), transparent 60%); }
        .grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
            align-items: center;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            font-size: 0.82rem;
        }
        h1 {
            font-size: clamp(2.4rem, 3vw, 3rem);
            margin: 14px 0 12px;
            color: #f8fafc;
        }
        p {
            margin: 0 0 20px;
            color: var(--muted);
            line-height: 1.6;
            font-size: 1.02rem;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
        }
        .btn {
            border: 1px solid var(--border);
            padding: 14px 18px;
            border-radius: 14px;
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn.primary {
            background: linear-gradient(120deg, #3b82f6, #8b5cf6);
            border-color: rgba(96,165,250,0.4);
            color: #0b1020;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.25);
            border-color: rgba(96,165,250,0.6);
        }
        .card {
            padding: 18px;
            border-radius: 18px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
        }
        .status {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 0.95rem;
        }
        .status strong { color: #f8fafc; }
        .mono {
            font-family: "SFMono-Regular", Menlo, Consolas, "Liberation Mono", monospace;
            color: #cbd5e1;
            font-size: 0.95rem;
            word-break: break-all;
        }
        svg {
            width: 56px;
            height: 56px;
        }
        @media (max-width: 640px) {
            .shell { padding: 26px; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <div class="grid">
            <div>
                <div class="badge">Erro 404</div>
                <h1>Essa página se perdeu no caminho.</h1>
                <p>A URL acessada não existe ou foi movida. Use os botões abaixo para voltar à navegação segura.</p>
                <div class="actions">
                    <a class="btn primary" href="/">Ir para a página inicial</a>
                    <a class="btn" href="javascript:history.back()">Voltar</a>
                </div>
            </div>
            <div class="card">
                <div class="status">
                    <svg viewBox="0 0 64 64" fill="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="grad" x1="0" x2="1" y1="0" y2="1">
                                <stop stop-color="#60a5fa" offset="0%"/>
                                <stop stop-color="#a855f7" offset="100%"/>
                            </linearGradient>
                        </defs>
                        <path d="M12 22a6 6 0 0 1 6-6h28a6 6 0 0 1 6 6v20a6 6 0 0 1-6 6H18a6 6 0 0 1-6-6V22Z" stroke="url(#grad)" stroke-width="3" fill="rgba(255,255,255,0.02)" />
                        <path d="M20 26h24M20 34h14" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="44" cy="34" r="3" fill="#a855f7"/>
                        <path d="M28 14v-2a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2" stroke="#60a5fa" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <strong>URL acessada</strong>
                        <div class="mono"><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
