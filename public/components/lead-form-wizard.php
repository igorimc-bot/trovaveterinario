<div class="lead-form-wizard-wrapper">
    <!-- Progress Indicator -->
    <div class="wizard-progress">
        <div class="wizard-step active" data-step="1">1</div>
        <div class="wizard-line"></div>
        <div class="wizard-step" data-step="2">2</div>
        <div class="wizard-line"></div>
        <div class="wizard-step" data-step="3">3</div>
    </div>

    <form id="leadFormWizard" class="lead-form-wizard" method="POST" action="/api/submit-lead.php">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <!-- Context Data (populated by PHP context) -->
        <?php if (isset($locationType)): ?>
            <input type="hidden" name="location_type" value="<?= $locationType ?>">
        <?php endif; ?>
        <?php if (isset($location['id'])): ?>
            <input type="hidden" name="location_id" value="<?= $location['id'] ?>">
        <?php endif; ?>
        <?php if (isset($servizio['id'])): ?>
            <input type="hidden" name="servizio_id" value="<?= $servizio['id'] ?>">
        <?php endif; ?>

        <!-- Step 1: Contatti -->
        <div class="wizard-step-content active" data-step="1">
            <h3 class="text-center mb-4">I tuoi Dati di Contatto</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input type="text" id="nome" name="nome" required>
                    <span class="error-msg"></span>
                </div>
                <div class="form-group">
                    <label for="cognome">Cognome *</label>
                    <input type="text" id="cognome" name="cognome" required>
                    <span class="error-msg"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error-msg"></span>
                </div>
                <div class="form-group">
                    <label for="telefono">Telefono *</label>
                    <input type="tel" id="telefono" name="telefono" required>
                    <span class="error-msg"></span>
                </div>
            </div>

            <div class="wizard-actions">
                <button type="button" class="btn btn-primary btn-next">Avanti →</button>
            </div>
        </div>

        <!-- Step 2: Dettagli -->
        <div class="wizard-step-content" data-step="2">
            <h3 class="text-center mb-4">Dettagli della Richiesta</h3>

            <!-- Se non siamo in una pagina specifica di servizio, mostriamo la select -->
            <?php if (!isset($servizio['id'])): ?>
                <div class="form-group">
                    <label for="servizio_select">Servizio di Interesse *</label>
                    <select id="servizio_select" name="servizio_id_select" required>
                        <option value="">Seleziona un servizio...</option>
                        <?php
                        $allServizi = getAllServizi();
                        foreach ($allServizi as $s): ?>
                            <option value="<?= $s['id'] ?>">
                                <?= htmlspecialchars($s['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Se non siamo in una pagina specifica di location, mostriamo le select geografiche -->
            <?php if (!isset($location['id'])): ?>
                <div class="form-group">
                    <label for="regione_select">Regione di Interesse</label>
                    <select id="regione_select" name="regione_id_select">
                        <option value="">Tutta Italia</option>
                        <?php
                        $allRegioni = getAllRegioni();
                        foreach ($allRegioni as $r): ?>
                            <option value="<?= $r['id'] ?>">
                                <?= htmlspecialchars($r['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="descrizione">Note o Richieste Particolari</label>
                <textarea id="descrizione" name="descrizione" rows="3"
                    placeholder="Descrivi brevemente cosa stai cercando..."></textarea>
            </div>

            <div class="form-group">
                <label>Preferenza di Contatto</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="preferenza_contatto" value="telefono" checked> Telefono
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="preferenza_contatto" value="whatsapp"> WhatsApp
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="preferenza_contatto" value="email"> Email
                    </label>
                </div>
            </div>

            <div class="wizard-actions space-between">
                <button type="button" class="btn btn-outline btn-prev">← Indietro</button>
                <button type="button" class="btn btn-primary btn-next">Avanti →</button>
            </div>
        </div>

        <!-- Step 3: Privacy e Invio -->
        <div class="wizard-step-content" data-step="3">
            <h3 class="text-center mb-4">Conferma e Invia</h3>

            <div class="summary-box">
                <p><strong>Riepilogo dati:</strong></p>
                <ul id="summary-list">
                    <!-- Populated by JS -->
                </ul>
            </div>

            <div class="form-group checkbox-group">
                <label style="align-items: flex-start;">
                    <input type="checkbox" name="privacy" id="privacy" required style="margin-top: 4px;">
                    <span style="font-size: 0.9rem; line-height: 1.4; display: block;">
                        Dichiaro di aver letto e compreso l'<a href="/privacy-policy" target="_blank">Informativa sulla
                            Privacy</a> e i <a href="/termini-condizioni" target="_blank">Termini e Condizioni
                            d'Uso</a>.
                        <br>
                        <span class="text-muted" style="font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                            Autorizzo il trattamento dei miei dati personali ai sensi degli Artt. 13 e 14 del
                            Regolamento UE 2016/679 (GDPR) per le finalità legate alla gestione della richiesta.
                        </span>
                    </span>
                </label>
                <span class="error-msg"></span>
            </div>

            <div class="wizard-actions space-between">
                <button type="button" class="btn btn-outline btn-prev">← Indietro</button>
                <button type="submit" class="btn btn-primary btn-lg btn-submit">Invia Richiesta</button>
            </div>

            <div id="formMessageWizard" class="form-message mt-3"></div>
        </div>

    </form>
</div>