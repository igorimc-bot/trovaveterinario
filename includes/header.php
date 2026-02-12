<!DOCTYPE html>
<html lang="it">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ERNF5M764V"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-ERNF5M764V');

        // Custom Tracking Script
        (function () {
            const SITE_ID = 4;
            const BASE_URL = 'https://dashboard.bbproservice.it/api.php';

            function track(type) {
                fetch(`${BASE_URL}?site_id=${SITE_ID}&type=${type}`)
                    .catch(e => console.error('Tracking error:', e));
            }

            // Check if visited in this session
            if (!sessionStorage.getItem('ag24_visited')) {
                // First visit in session
                track('visit');
                track('page_view');
                sessionStorage.setItem('ag24_visited', 'true');
            } else {
                // Subsequent page views
                track('page_view');
            }
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php if (isset($metaTitle)): ?>
        <title>
            <?= htmlspecialchars($metaTitle) ?>
        </title>
    <?php else: ?>
        <title>Aste Giudiziarie 24 - Assistenza Completa per Aste in Italia</title>
    <?php endif; ?>

    <?php if (isset($metaDescription)): ?>
        <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php else: ?>
        <meta name="description"
            content="Assistenza completa per aste giudiziarie e fallimentari in Italia. Consulenza gratuita, supporto legale, perizie. Trova le migliori opportunità nella tua zona.">
    <?php endif; ?>

    <?php if (isset($canonical)): ?>
        <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
    <?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle ?? 'Aste Giudiziarie 24') ?>">
    <meta property="og:description"
        content="<?= htmlspecialchars($metaDescription ?? 'Assistenza completa per aste giudiziarie e fallimentari') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical ?? APP_URL) ?>">
    <meta property="og:site_name" content="Aste Giudiziarie 24">

    <!-- Favicon -->
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/img/favicon.svg">
    <link rel="alternate icon" type="image/x-icon" href="/assets/img/favicon.ico">

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= time() ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Schema Markup -->
    <?php if (isset($schemaMarkup)): ?>
        <script type="application/ld+json">
                                                <?= json_encode($schemaMarkup, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
                                                </script>
    <?php endif; ?>

    <?php if (!empty(RECAPTCHA_SITE_KEY)): ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>"></script>
    <?php endif; ?>
</head>

<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">
                        <h1>Aste Giudiziarie 24</h1>
                    </a>
                </div>

                <nav class="main-nav">
                    <button class="mobile-menu-toggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <ul class="nav-menu">
                        <li><a href="/">Home</a></li>
                        <?php
                        $isHome = $_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/index.php';
                        $linkPrefix = $isHome ? '' : '/';
                        ?>
                        <li><a href="<?= $linkPrefix ?>#servizi">Servizi</a></li>
                        <li><a href="<?= $linkPrefix ?>#regioni">Regioni</a></li>
                        <li><a href="/pubblicita">Pubblicità</a></li>
                        <li><a href="#contatti" class="btn-primary-small text-white">Contattaci</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="site-main">