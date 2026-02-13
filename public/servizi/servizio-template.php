<?php
/**
 * Servizio Template
 * Dynamic page for service-specific content
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// $servizio is set by router.php
if (!isset($servizio) || !$servizio) {
    http_response_code(404);
    require PUBLIC_PATH . '/404.php';
    exit;
}

// Get related data
$regioni = getAllRegioni();

// SEO Meta
$metaTitle = $servizio['meta_title'] ?? "{$servizio['nome']} | Trova Veterinario";
$metaDescription = $servizio['meta_description'] ?? "Trova i migliori specialisti per {$servizio['nome']}. Consulenza, visite e pronto soccorso in tutta Italia. Contattaci ora!";
$canonical = APP_URL . '/servizi/' . $servizio['slug'];

// Breadcrumb
$breadcrumbItems = [
    ['name' => 'Home', 'url' => APP_URL],
    ['name' => 'Servizi', 'url' => APP_URL . '/#servizi'],
    ['name' => $servizio['nome'], 'url' => $canonical]
];

// Schema Markup
$serviceSchema = generateServiceSchema($servizio);
$breadcrumbSchema = generateBreadcrumbSchema($breadcrumbItems);

// Include header
include __DIR__ . '/../../includes/header.php';
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="container">
        <?= renderBreadcrumbs($breadcrumbItems) ?>
    </div>
</section>

<!-- Hero Section -->
<section class="hero hero-small" <?php if (!empty($servizio['immagine'])): ?>style="background-image: url('/<?= ltrim(htmlspecialchars($servizio['immagine']), '/') ?>'); background-size: cover; background-position: center;"
    <?php endif; ?>>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                <?= htmlspecialchars($servizio['nome']) ?>
            </h1>
            <p class="hero-subtitle">
                <?= htmlspecialchars($servizio['descrizione_breve']) ?>
            </p>
        </div>
    </div>
</section>

<!-- Service Content -->
<?php if (!empty($servizio['contenuto'])): ?>
    <section class="content-section">
        <div class="container">
            <div class="content-wrapper">
                <?= $servizio['contenuto'] ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Benefits Section -->
<section class="why-us-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Perché Scegliere Trova Veterinario</h2>
            <p>La salute del tuo animale è la nostra priorità</p>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🐾</div>
                <h3>Specialisti Certificati</h3>
                <p>Collaboriamo solo con veterinari qualificati e strutture d'eccellenza.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🚑</div>
                <h3>Pronto Soccorso</h3>
                <p>Reperibilità per urgenze e cliniche aperte 24 ore su 24.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">📍</div>
                <h3>Vicino a Te</h3>
                <p>Trova facilmente lo studio veterinario più comodo nella tua zona.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🔬</div>
                <h3>Tecnologia Avanzata</h3>
                <p>Accesso a cliniche dotate delle più moderne strumentazioni diagnostiche.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">❤️</div>
                <h3>Amore per gli Animali</h3>
                <p>Passione e dedizione sono al centro di ogni visita e trattamento.</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">📅</div>
                <h3>Facilità di Contatto</h3>
                <p>Richiedi appuntamenti o preventivi in pochi click.</p>
            </div>
        </div>
    </div>
</section>

<!-- Regions Section -->
<section class="locations-section">
    <div class="container">
        <div class="section-header">
            <h2>Disponibile in Tutte le Regioni</h2>
            <p>Seleziona la tua regione per informazioni specifiche</p>
        </div>

        <div class="locations-grid">
            <?php foreach ($regioni as $regione): ?>
                <a href="/<?= $servizio['slug'] ?>/<?= $regione['slug'] ?>" class="location-card">
                    <h3>
                        <?= htmlspecialchars($regione['nome']) ?>
                    </h3>
                    <span class="location-arrow">→</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section id="contatti" class="contact-section">
    <div class="container">
        <div class="section-header">
            <h2>Richiedi Consulenza Gratuita</h2>
            <p>Compila il form e ti ricontatteremo entro 24 ore</p>
        </div>

        <div class="contact-form-wrapper">
            <form id="leadForm" class="lead-form" method="POST" action="/api/submit-lead.php">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="servizio_id" value="<?= $servizio['id'] ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="cognome">Cognome *</label>
                        <input type="text" id="cognome" name="cognome" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Telefono *</label>
                        <input type="tel" id="telefono" name="telefono" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="regione_id">Regione *</label>
                    <select id="regione_id" name="regione_id" required>
                        <option value="">Seleziona una regione</option>
                        <?php foreach ($regioni as $regione): ?>
                            <option value="<?= $regione['id'] ?>">
                                <?= htmlspecialchars($regione['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descrizione">Descrizione della Richiesta</label>
                    <textarea id="descrizione" name="descrizione" rows="4"
                        placeholder="Descrivi brevemente la tua richiesta..."></textarea>
                </div>

                <div class="form-group">
                    <label for="preferenza_contatto">Preferenza di Contatto</label>
                    <select id="preferenza_contatto" name="preferenza_contatto">
                        <option value="telefono">Telefono</option>
                        <option value="email">Email</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="privacy" required>
                        Accetto la <a href="/privacy-policy" target="_blank">Privacy Policy</a> *
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">Invia Richiesta</button>
                </div>

                <div id="formMessage" class="form-message"></div>
            </form>
        </div>
    </div>
</section>

<!-- Schema Markup -->
<script type="application/ld+json">
<?= $serviceSchema ?>
</script>

<script type="application/ld+json">
<?= $breadcrumbSchema ?>
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>