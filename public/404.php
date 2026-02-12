<?php
/**
 * 404 Not Found Page
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

http_response_code(404);

$metaTitle = 'Pagina Non Trovata - Trova Veterinario';
$metaDescription = 'La pagina che stai cercando non esiste. Torna alla homepage o esplora i nostri servizi.';

include __DIR__ . '/../includes/header.php';
?>

<section class="error-page">
    <div class="container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2>Pagina Non Trovata</h2>
            <p>La pagina che stai cercando non esiste o è stata spostata.</p>

            <div class="error-actions">
                <a href="/" class="btn btn-primary">Torna alla Homepage</a>
                <a href="#contatti" class="btn btn-secondary">Contattaci</a>
            </div>

            <div class="error-suggestions">
                <h3>Potrebbero interessarti:</h3>
                <ul>
                    <?php
                    $servizi = getAllServizi();
                    foreach (array_slice($servizi, 0, 5) as $servizio):
                        ?>
                        <li><a href="/servizi/<?= $servizio['slug'] ?>">
                                <?= htmlspecialchars($servizio['nome']) ?>
                            </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<style>
    .error-page {
        padding: 100px 0;
        text-align: center;
    }

    .error-code {
        font-size: 8rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .error-content h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .error-content p {
        font-size: 1.25rem;
        color: var(--text-light);
        margin-bottom: 2rem;
    }

    .error-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-bottom: 3rem;
    }

    .error-suggestions {
        max-width: 600px;
        margin: 0 auto;
    }

    .error-suggestions ul {
        list-style: none;
        padding: 0;
    }

    .error-suggestions li {
        margin-bottom: 0.5rem;
    }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>