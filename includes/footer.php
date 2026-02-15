</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Trova Veterinario</h3>
                <p>Il portale numero uno in Italia per trovare il veterinario giusto per il tuo animale.</p>
                <p>Cani, gatti, esotici e animali da fattoria.</p>
            </div>

            <div class="footer-section">
                <h4>Servizi</h4>
                <ul>
                    <?php
                    $servizi = getAllServizi();
                    foreach (array_slice($servizi, 0, 6) as $footerServizio):
                        ?>
                        <li><a href="/servizi/<?= $footerServizio['slug'] ?>">
                                <?= htmlspecialchars($footerServizio['nome']) ?>
                            </a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Regioni Principali</h4>
                <ul>
                    <?php
                    $regioni = getAllRegioni();
                    $principali = ['Lombardia', 'Lazio', 'Veneto', 'Piemonte', 'Emilia-Romagna', 'Toscana'];
                    foreach ($regioni as $footerRegione):
                        if (in_array($footerRegione['nome'], $principali)):
                            ?>
                            <li><a href="/regioni/<?= $footerRegione['slug'] ?>">
                                    <?= htmlspecialchars($footerRegione['nome']) ?>
                                </a></li>
                            <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Informazioni</h4>
                <ul>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/cookie-policy">Cookie Policy</a></li>
                    <li><a href="/termini-condizioni">Termini e Condizioni</a></li>
                    <li><a href="/pubblicita">Pubblicità</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy;
                <?= date('Y') ?> Trova Veterinario. Tutti i diritti riservati.
            </p>
        </div>
    </div>
</footer>

<!-- Cookie Consent Banner -->
<div id="cookie-consent-banner">
    <div class="cookie-content">
        <div class="cookie-text">
            <h3>Questo sito utilizza i cookie</h3>
            <p>
                Utilizziamo i cookie per migliorare la tua esperienza di navigazione.
                <a href="/cookie-policy" target="_blank">Maggiori informazioni</a>
            </p>
        </div>
        <div class="cookie-buttons">
            <button id="cookie-accept-all" class="cookie-btn">Accetta tutti</button>
            <button id="cookie-reject-all" class="cookie-btn">Rifiuta tutti</button>
            <button id="cookie-manage" class="cookie-btn">Gestisci preferenze</button>
        </div>
    </div>
</div>

<!-- Cookie Preferences Modal -->
<div id="cookie-preferences-modal">
    <div class="cookie-modal-content">
        <div class="cookie-modal-header">
            <h2>Gestisci le tue preferenze sui cookie</h2>
            <button id="cookie-modal-close">&times;</button>
        </div>
        <div class="cookie-modal-body">
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <h3>Cookie Necessari</h3>
                    <label class="cookie-toggle">
                        <input type="checkbox" checked disabled>
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                <p>
                    Questi cookie sono essenziali per il funzionamento del sito e non possono essere disabilitati.
                    Vengono utilizzati per la navigazione e per fornire le funzionalità di base.
                </p>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <h3>Cookie Analitici</h3>
                    <label class="cookie-toggle">
                        <input type="checkbox" id="cookie-analytics">
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                <p>
                    Questi cookie ci aiutano a capire come i visitatori interagiscono con il nostro sito.
                    Raccogliamo e analizziamo dati in forma anonima tramite Google Analytics.
                </p>
            </div>
        </div>
        <div class="cookie-modal-footer">
            <button id="cookie-save-preferences" class="cookie-btn">Salva preferenze</button>
        </div>
    </div>
</div>

<script src="/assets/js/cookie-consent.js"></script>

<!-- JavaScript -->
<script src="/assets/js/main.js?v=<?= time() ?>"></script>
<script src="/assets/js/lead-form.js?v=<?= time() ?>"></script>

<?php if (!empty(RECAPTCHA_SITE_KEY)): ?>
    <script>
        // reCAPTCHA v3 integration
        function executeRecaptcha(action) {
            return grecaptcha.execute('<?= RECAPTCHA_SITE_KEY ?>', { action: action });
        }
    </script>
<?php endif; ?>
</body>

</html>