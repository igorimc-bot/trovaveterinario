<?php
/**
 * Pubblicità Page
 * Landing page for professionals who want to advertise
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$metaTitle = 'Pubblicità - Diventa Partner | Trova Veterinario';
$metaDescription = 'Sei un veterinario, hai una clinica o un negozio di animali? Entra nel nostro network e ricevi nuovi clienti qualificati.';
$canonical = APP_URL . '/pubblicita';

include __DIR__ . '/../includes/header.php';
?>

<section class="hero hero-small">
    <div class="hero-overlay"></div>
    <div class="container hero-container">
        <h1 class="hero-title">Diventa Partner di Trova Veterinario</h1>
        <p class="hero-subtitle">Entra nel principale network italiano per la salute animale e ricevi prenotazioni nella
            tua zona</p>
    </div>
</section>

<section class="why-us-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Perché Diventare Partner</h2>
            <p>Fai crescere la tua attività con noi</p>
        </div>

        <div class="advertising-grid">
            <div class="benefit-card">
                <div class="benefit-icon">📈</div>
                <h3>Nuovi Clienti</h3>
                <p>Ricevi richieste di appuntamento da proprietari di animali nella tua zona</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🎯</div>
                <h3>Visibilità Mirata</h3>
                <p>Fatti trovare da chi cerca esattamente i tuoi servizi specialistici</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">⭐</div>
                <h3>Reputazione Online</h3>
                <p>Costruisci la tua presenza digitale su un portale affidabile e verticale</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🤝</div>
                <h3>Nessun Costo Fisso</h3>
                <p>Paghi solo per i risultati reali o scegli piani flessibili</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">📅</div>
                <h3>Gestione Semplice</h3>
                <p>Ricevi le richieste via email o gestionale e organizza la tua agenda</p>
            </div>

            <div class="benefit-card">
                <div class="benefit-icon">🚀</div>
                <h3>Supporto Marketing</h3>
                <p>Campagne pubblicitarie attive tutto l'anno per portarti traffico</p>
            </div>
        </div>
    </div>
</section>

<section class="services-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Chi Stiamo Cercando</h2>
            <p>Professionisti qualificati per la cura degli animali</p>
        </div>

        <div class="advertising-grid">
            <div class="service-card">
                <div class="service-icon">👨‍⚕️</div>
                <h3>Medici Veterinari</h3>
                <p>Specialisti in animali da compagnia, esotici o da fattoria, disponibili per visite in studio o a
                    domicilio.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🏥</div>
                <h3>Cliniche e Ambulatori</h3>
                <p>Strutture attrezzate per chirurgia, diagnostica, degenza e pronto soccorso 24/7.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">✂️</div>
                <h3>Toelettatori</h3>
                <p>Professionisti della bellezza e igiene animale.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🐕</div>
                <h3>Educatori Cinofili</h3>
                <p>Esperti in addestramento e comportamento animale.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🦴</div>
                <h3>Pensioni per Animali</h3>
                <p>Strutture ricettive per ospitare cani, gatti e altri animali.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🏪</div>
                <h3>Pet Shop</h3>
                <p>Negozi di alimenti e accessori per animali che vogliono aumentare la visibilità locale.</p>
            </div>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>Richiedi Informazioni</h2>
            <p>Compila il form per scoprire come possiamo aiutarti a trovare nuovi clienti</p>
        </div>

        <div class="contact-form-wrapper">
            <form id="advertisingForm" class="lead-form" method="POST" action="/api/submit-advertising.php">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome Referente *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="azienda">Nome Studio/Attività</label>
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
                    <label for="tipologia">Tipologia Attività *</label>
                    <select id="tipologia" name="tipologia" required>
                        <option value="">Seleziona...</option>
                        <option value="veterinario_libero_professionista">Veterinario Libero Professionista</option>
                        <option value="clinica_veterinaria">Clinica Veterinaria</option>
                        <option value="toelettatura">Toelettatura</option>
                        <option value="educatore">Educatore Cinofilo</option>
                        <option value="pensione">Pensione Animali</option>
                        <option value="pet_shop">Pet Shop</option>
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