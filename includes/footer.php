</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Aste Giudiziarie 24</h3>
                <p>Assistenza completa per aste giudiziarie e fallimentari in tutta Italia.</p>
                <p>Consulenza gratuita, supporto legale, perizie professionali.</p>
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
                <?= date('Y') ?> Aste Giudiziarie 24. Tutti i diritti riservati.
            </p>
        </div>
    </div>
</footer>

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