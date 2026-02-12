<?php
/**
 * Pubblicità Page
 * Landing page for professionals who want to advertise
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$metaTitle = 'Pubblicità - Diventa Partner | Aste Giudiziarie 24';
$metaDescription = 'Sei un professionista del settore aste? Avvocato, perito, consulente? Entra nel nostro network e ricevi lead qualificati.';
$canonical = APP_URL . '/pubblicita';

include __DIR__ . '/../includes/header.php';
?>

<section class="hero hero-small">
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <h1 class="hero-title">Diventa Partner di Aste Giudiziarie 24</h1>
        <p class="hero-subtitle">Entra nel nostro network di professionisti e ricevi lead qualificati nella tua zona</p>
    </div>
</section>

<section class="why-us-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Perché Diventare Partner</h2>
            <p>Unisciti al network leader nelle aste giudiziarie</p>
        </div>

        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">📈</div>
                <h3>Lead Qualificati</h3>
                <p>Ricevi contatti di clienti realmente interessati ai tuoi servizi</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🎯</div>
                <h3>Targeting Geografico</h3>
                <p>Lead nella tua zona di competenza</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">💼</div>
                <h3>Visibilità Nazionale</h3>
                <p>Presenza su un portale visitato da migliaia di utenti</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🤝</div>
                <h3>Supporto Dedicato</h3>
                <p>Assistenza continua per massimizzare i risultati</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🔓</div>
                <h3>Massima Flessibilità</h3>
                <p>Gestisci la tua disponibilità in base ai tuoi impegni</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🚀</div>
                <h3>Crescita del Business</h3>
                <p>Espandi la tua clientela in modo costante e misurabile</p>
            </div>
        </div>
    </div>
</section>

<section class="services-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Chi Stiamo Cercando</h2>
            <p>Professionisti qualificati per offrire un servizio d'eccellenza</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">⚖️</div>
                <h3>Avvocati Specializzati</h3>
                <p>Esperti in diritto fallimentare, esecuzioni immobiliari, e procedure concorsuali.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">📐</div>
                <h3>Periti e Geometri</h3>
                <p>Professionisti per perizie immobiliari, valutazioni e stime patrimoniali.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">💰</div>
                <h3>Consulenti Finanziari</h3>
                <p>Esperti in mutui e finanziamenti specifici per acquisti all'asta.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🏗️</div>
                <h3>Imprese Ristrutturazioni</h3>
                <p>Aziende qualificate per lavori di ristrutturazione post-acquisto.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🏢</div>
                <h3>Agenzie Immobiliari</h3>
                <p>Agenti immobiliari interessati a proporre opportunità d'asta ai propri clienti.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">📊</div>
                <h3>Commercialisti</h3>
                <p>Professionisti per la gestione fiscale e tributaria di investimenti immobiliari.</p>
            </div>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Richiedi Informazioni</h2>
            <p>Compila il form e ti ricontatteremo per illustrarti le opportunità di partnership</p>
        </div>

        <div class="contact-form-wrapper">
            <form id="advertisingForm" class="lead-form" method="POST" action="/api/submit-advertising.php">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="azienda">Azienda/Studio</label>
                        <input type="text" id="azienda" name="azienda">
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
                    <label for="tipologia">Tipologia Professionista *</label>
                    <select id="tipologia" name="tipologia" required>
                        <option value="">Seleziona...</option>
                        <option value="avvocato">Avvocato</option>
                        <option value="perito">Perito/Geometra</option>
                        <option value="consulente_finanziario">Consulente Finanziario</option>
                        <option value="impresa_ristrutturazioni">Impresa Ristrutturazioni</option>
                        <option value="agenzia_immobiliare">Agenzia Immobiliare</option>
                        <option value="commercialista">Commercialista</option>
                        <option value="altro">Altro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="messaggio">Messaggio</label>
                    <textarea id="messaggio" name="messaggio" rows="4"
                        placeholder="Raccontaci della tua attività e delle tue esigenze..."></textarea>
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



<script>
    // Advertising form submission
    document.getElementById('advertisingForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        const formMessage = document.getElementById('formMessage');
        const originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Invio in corso...';
        formMessage.textContent = '';
        formMessage.className = 'form-message';

        try {
            const formData = new FormData(this);
            const response = await fetch('/api/submit-advertising.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Tracking Lead
                fetch('https://dashboard.bbproservice.it/api.php?site_id=4&type=lead')
                    .catch(e => console.error('Tracking error:', e));

                formMessage.textContent = data.message;
                formMessage.classList.add('success');
                this.reset();
            } else {
                formMessage.textContent = data.error || 'Si è verificato un errore.';
                formMessage.classList.add('error');
            }
        } catch (error) {
            formMessage.textContent = 'Errore di connessione. Riprova più tardi.';
            formMessage.classList.add('error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>