<?php
// /app/Views/layouts/base.php
// Layout base global com todas as dependências (Tailwind, Lucide, Geist)
use App\Core\Database;
use App\Core\Auth;
use App\Core\Security;

if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('X-UA-Compatible: IE=edge');
}
$faviconFallbackPath = '/uploads/fallback-images/favicon-fallback.svg';
$faviconPath = $faviconFallbackPath;
$logoLightPath = '/uploads/fallback-images/logo-light-fallback.svg';
$logoDarkPath = '/uploads/fallback-images/logo-dark-fallback.svg';
$siteName = 'FerramentasAi';
$primaryColor = '#2563eb';
$primaryHover = '#1d4ed8';
$secondaryColor = '#db0000';
$secondaryHover = '#b00000';
$themePreference = 'system';

// Recupera favicon das configurações
try {
    $pdo = Database::getConnection();
    $settingsTable = Database::table('settings');
    $prefsTable = Database::table('user_preferences');
    $settingsRow = $pdo->query("SELECT org_name, favicon_path, logo_light_path, logo_dark_path, primary_color, secondary_color FROM {$settingsTable} LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
    if (!empty($settingsRow['favicon_path'])) {
        $faviconPath = $settingsRow['favicon_path'];
    }
    if (!empty($settingsRow['logo_light_path'])) {
        $logoLightPath = $settingsRow['logo_light_path'];
    }
    if (!empty($settingsRow['logo_dark_path'])) {
        $logoDarkPath = $settingsRow['logo_dark_path'];
    }
    if (!empty($settingsRow['org_name'])) {
        $siteName = $settingsRow['org_name'];
    }

    $primaryFromDb = $settingsRow['primary_color'] ?? null;
    if (!empty($primaryFromDb)) {
        $primaryColor = $primaryFromDb;
        // função simples para escurecer a cor
        $hex = ltrim($primaryColor, '#');
        if (strlen($hex) === 6) {
            $r = max(0, (int)hexdec(substr($hex, 0, 2)) - 20);
            $g = max(0, (int)hexdec(substr($hex, 2, 2)) - 20);
            $b = max(0, (int)hexdec(substr($hex, 4, 2)) - 20);
            $primaryHover = sprintf('#%02x%02x%02x', $r, $g, $b);
        }
    }

    $secondaryFromDb = $settingsRow['secondary_color'] ?? null;
    if (!empty($secondaryFromDb)) {
        $secondaryColor = $secondaryFromDb;
        $hex = ltrim($secondaryColor, '#');
        if (strlen($hex) === 6) {
            $r = max(0, (int)hexdec(substr($hex, 0, 2)) - 25);
            $g = max(0, (int)hexdec(substr($hex, 2, 2)) - 25);
            $b = max(0, (int)hexdec(substr($hex, 4, 2)) - 25);
            $secondaryHover = sprintf('#%02x%02x%02x', $r, $g, $b);
        }
    }
    $userId = Auth::user()?->id;
    if ($userId) {
        $stmt = $pdo->prepare("SELECT theme_preference FROM {$prefsTable} WHERE user_id = :uid LIMIT 1");
        $stmt->execute(['uid' => $userId]);
        $themePreference = $stmt->fetchColumn() ?: 'system';
    }
} catch (\Throwable $e) {
    // silencioso para não quebrar layout
}
// Monta título final
$finalTitle = $title ?? null;
if ($finalTitle) {
    $finalTitle = $finalTitle . ' · ' . $siteName;
} else {
    $finalTitle = $siteName;
}
?>
<!DOCTYPE html>
<html lang="pt-br" class="h-full scroll-smooth" data-server-theme="<?= htmlspecialchars($themePreference, ENT_QUOTES, 'UTF-8') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="<?= htmlspecialchars(Security::csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($finalTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= $description ?? 'Plataforma de ferramentas inteligentes' ?>">
    <?php if (!empty($faviconPath)): ?>
        <link rel="icon" href="<?= htmlspecialchars($faviconPath, ENT_QUOTES, 'UTF-8') ?>" type="image/x-icon">
    <?php endif; ?>
    <style>
        :root {
            --primary-color: <?= htmlspecialchars($primaryColor, ENT_QUOTES, 'UTF-8') ?>;
            --primary-color-hover: <?= htmlspecialchars($primaryHover, ENT_QUOTES, 'UTF-8') ?>;
            --color-primary: <?= htmlspecialchars($primaryColor, ENT_QUOTES, 'UTF-8') ?>;
            --color-primary-hover: <?= htmlspecialchars($primaryHover, ENT_QUOTES, 'UTF-8') ?>;
            --secondary-color: <?= htmlspecialchars($secondaryColor, ENT_QUOTES, 'UTF-8') ?>;
            --secondary-color-hover: <?= htmlspecialchars($secondaryHover, ENT_QUOTES, 'UTF-8') ?>;
        }
        .bg-blue-600 { background-color: var(--primary-color) !important; }
        .hover\:bg-blue-700:hover { background-color: var(--primary-color-hover) !important; }
    </style>
    <script>
        (() => {
            const STORAGE_KEY = 'themePreference';
            const serverPref = '<?= htmlspecialchars($themePreference, ENT_QUOTES, "UTF-8") ?>';
            const mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
            const prefersDark = () => mediaQuery ? mediaQuery.matches : false;
            const safeGetStorage = () => {
                try {
                    return localStorage.getItem(STORAGE_KEY);
                } catch {
                    return null;
                }
            };
            const safeSetStorage = (value) => {
                try {
                    if (value === 'system') {
                        localStorage.removeItem(STORAGE_KEY);
                    } else {
                        localStorage.setItem(STORAGE_KEY, value);
                    }
                } catch {
                    // ignore storage errors
                }
            };

            let currentPref = safeGetStorage() ?? serverPref;

            const applyTheme = (pref) => {
                const next = pref === 'dark' || (pref === 'system' && prefersDark());
                document.documentElement.classList.toggle('dark', next);
                currentPref = pref;
            };

            window.__setThemePreference = (pref) => {
                const normalized = pref === 'dark' || pref === 'light' ? pref : 'system';
                safeSetStorage(normalized);
                applyTheme(normalized);
            };

            applyTheme(currentPref);

            if (mediaQuery) {
                mediaQuery.addEventListener('change', () => {
                    if (currentPref === 'system') {
                        applyTheme('system');
                    }
                });
            }
        })();
    </script>

    <!-- Geist Font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Global Styles -->
    <link rel="stylesheet" href="/css/global.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Geist"', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7e22ce',
                            800: '#6b21a8',
                            900: '#581c87',
                        },
                    },
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script defer src="https://unpkg.com/lucide@latest"></script>

    <!-- Additional Head Content -->
    <?php if (isset($head)): ?>
        <?= $head ?>
    <?php endif; ?>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-50 antialiased">

    <div class="hidden">
        <img src="<?= htmlspecialchars($logoLightPath, ENT_QUOTES, 'UTF-8') ?>" alt="logo-preload-light">
        <img src="<?= htmlspecialchars($logoDarkPath, ENT_QUOTES, 'UTF-8') ?>" alt="logo-preload-dark">
    </div>

    <?= $content ?>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
        
        // Forçar recalcular layout em navegadores com problemas de responsividade
        window.addEventListener('load', () => {
            // Trigger reflow
            void document.documentElement.offsetHeight;
            // Forçar recalcular media queries
            if (window.matchMedia) {
                const mediaQueries = [
                    '(max-width: 640px)',
                    '(min-width: 641px)',
                    '(max-width: 768px)',
                    '(min-width: 769px)',
                    '(max-width: 1024px)',
                    '(min-width: 1025px)'
                ];
                mediaQueries.forEach(query => {
                    window.matchMedia(query).addListener(() => {
                        void document.documentElement.offsetHeight;
                    });
                });
            }
        });
        
        // Reforçar viewport em navegadores problemáticos
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                void document.documentElement.offsetHeight;
            }, 100);
        });
    </script>

    <!-- Additional Scripts -->
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
</body>

</html>
