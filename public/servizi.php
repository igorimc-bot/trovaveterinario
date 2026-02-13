<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = db()->getConnection();
$servizi = $pdo->query("SELECT * FROM servizi WHERE attivo = 1 ORDER BY ordine ASC, id DESC")->fetchAll(PDO::FETCH_ASSOC);

$metaTitle = "I Nostri Servizi | Trova Veterinario";
$metaDescription = "Scopri tutti i servizi offerti da Trova Veterinario. Visite specialistiche, chirurgia, vaccinazioni, toelettatura e molto altro.";

include __DIR__ . '/includes/header.php';
?>

<style>
    .services-page-header {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/assets/img/hero-bg.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 5rem 0;
        text-align: center;
        margin-bottom: 4rem;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        padding-bottom: 4rem;
    }

    /* Card Design matching the requested image */
    .service-card {
        background: var(--bg-card);
        border-radius: var(--radius-lg);
        padding: 1rem;
        transition: var(--transition);
        display: flex;
        /* Keeping layout props */
        flex-direction: column;
        height: 100%;
        position: relative;
        border: none;
        /* Explicitly removing border as requested */
    }

    .service-card:hover {
        transform: translateY(-5px);
        /* box-shadow removed as requested */
    }

    .service-card-img {
        height: 280px;
        /* Increased height */
        width: 100%;
        background-color: transparent;
        position: relative;
        z-index: 1;
    }

    .service-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-fit: cover;
        border-radius: 0;
        /* Rounded all corners */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Curved bottom edge for image container overlap */
    .service-card-content {
        padding: 1.5rem;
        position: relative;
        background: white;
        margin: -50px 20px 0 20px;
        border-radius: 0;
        z-index: 2;
        flex: 1;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .service-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.25rem;
        /* Reduced margin */
        line-height: 1.3;
        min-height: 3.6rem;
        /* Reserve space for 2 lines */
        display: flex;
        align-items: flex-end;
        /* Align single lines to bottom? Or top? Usually top is standard, let's stick to standard flow or maybe flex-end looks better? No, top is better for reading. Default is top. */
        /* Actually user just said "space occupied is equal". */
    }

    .service-features {
        color: #ff9f43;
        /* Orange color */
        font-size: 0.9rem;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .service-divider {
        border-top: 1px dashed #e0e0e0;
        margin: 0.5rem 0 1rem 0;
    }

    .service-description {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .service-footer {
        margin-top: auto;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid #f5f5f5;
    }

    .service-price-label {
        font-weight: 500;
        color: #999;
        font-size: 0.9rem;
    }

    .service-price {
        color: #8cc63f;
        /* Green color */
        font-size: 1.35rem;
        font-weight: 700;
    }

    .no-img-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: linear-gradient(45deg, #e6e9f0 0%, #eef1f5 100%);
        color: #999;
        font-weight: 600;
        font-weight: 600;
        border-radius: 0;
        /* Rounded all corners */
    }

    /* Link wrapper */
    .service-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }
</style>

<div class="services-page-header">
    <div class="container">
        <h1>I Nostri Servizi</h1>
        <p>Soluzioni professionali per ogni tua esigenza</p>
    </div>
</div>

<div class="container">
    <div class="services-grid">
        <?php foreach ($servizi as $s): ?>
            <a href="/servizi/<?= $s['slug'] ?>" class="service-link">
                <article class="service-card">
                    <div class="service-card-img">
                        <?php if (!empty($s['immagine'])): ?>
                            <img src="<?= htmlspecialchars($s['immagine']) ?>" alt="<?= htmlspecialchars($s['nome']) ?>">
                        <?php else: ?>
                            <div class="no-img-placeholder">
                                Trova Veterinario
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="service-card-content">
                        <h2 class="service-title">
                            <?= htmlspecialchars($s['nome']) ?>
                        </h2>

                        <?php if (!empty($s['features'])): ?>
                            <div class="service-features">
                                Features:
                                <?= htmlspecialchars($s['features']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="service-divider"></div>

                        <p class="service-description">
                            <?= htmlspecialchars($s['descrizione_breve']) ?>
                        </p>

                        <?php if (!empty($s['prezzo'])): ?>
                            <div class="service-footer">
                                <span class="service-price-label">Price Per Person:</span>
                                <span class="service-price">
                                    $
                                    <?= number_format($s['prezzo'], 2, ',', '.') ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Fallback if no services
if (empty($servizi)) {
    echo '<div class="container"><p style="text-align:center; padding-bottom: 4rem;">Nessun servizio disponibile al momento.</p></div>';
}

include __DIR__ . '/includes/footer.php';
?>