<div class="lead-form-wrapper">
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

        <input type="hidden" id="comune_id_hidden" name="comune_id" value="">
        <input type="hidden" id="provincia_id_hidden" name="provincia_id" value="">
        <input type="hidden" id="regione_id_hidden" name="regione_id" value="">

        <div class="wizard-step-content active" data-step="1" style="display: block;">
            <h3 class="text-center mb-4">Richiedi Informazioni o Prenota</h3>

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

            <?php if (!isset($location['id'])): ?>
                <div class="form-group autocomplete-container" style="position: relative;">
                    <label for="comune_search">Comune di Interesse</label>
                    <input type="text" id="comune_search" placeholder="Inizia a digitare il comune..." autocomplete="off">
                    <ul id="comune_results" class="autocomplete-results"></ul>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="descrizione">Note o Richieste Particolari</label>
                <textarea id="descrizione" name="descrizione" rows="3"
                    placeholder="Descrivi brevemente cosa stai cercando..."></textarea>
            </div>

            <div class="form-group">
                <label>Preferenza di Contatto</label>
                <div class="radio-group" style="display: flex; gap: 15px;">
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

            <div class="form-group checkbox-group mt-3">
                <label style="align-items: flex-start; display: flex;">
                    <input type="checkbox" name="privacy" id="privacy" required
                        style="margin-top: 4px; margin-right: 8px;">
                    <span style="font-size: 0.9rem; line-height: 1.4; display: block;">
                        Dichiaro di aver letto e compreso l'<a href="/privacy-policy" target="_blank">Informativa sulla
                            Privacy</a> e i <a href="/termini-condizioni" target="_blank">Termini e Condizioni
                            d'Uso</a>.
                        <br>
                        <span class="text-muted"
                            style="font-size: 0.8rem; display: block; margin-top: 0.5rem; color: #6c757d;">
                            Autorizzo il trattamento dei miei dati personali ai sensi degli Artt. 13 e 14 del
                            Regolamento UE 2016/679 (GDPR) per le finalità legate alla gestione della richiesta.
                        </span>
                    </span>
                </label>
                <span class="error-msg"></span>
            </div>

            <div class="wizard-actions mt-4 text-center">
                <button type="submit" class="btn btn-primary btn-lg btn-submit w-100" style="width: 100%;">Invia
                    Richiesta</button>
            </div>

            <div id="formMessageWizard" class="form-message mt-3"></div>
        </div>
    </form>
</div>

<style>
    .autocomplete-results {
        display: none;
        position: absolute;
        z-index: 1000;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
        list-style: none;
        padding: 0;
        margin: 4px 0 0 0;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .autocomplete-results li {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .autocomplete-results li:hover {
        background-color: #f8f9fa;
    }

    .autocomplete-results li:last-child {
        border-bottom: none;
    }
</style>