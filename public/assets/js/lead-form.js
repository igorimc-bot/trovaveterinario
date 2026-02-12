document.addEventListener('DOMContentLoaded', function () {
    const wizard = document.getElementById('leadFormWizard');
    if (!wizard) return;

    const steps = wizard.querySelectorAll('.wizard-step-content');
    const indicators = wizard.querySelectorAll('.wizard-step');
    const nextBtns = wizard.querySelectorAll('.btn-next');
    const prevBtns = wizard.querySelectorAll('.btn-prev');
    let currentStep = 1;

    // Show initial step
    showStep(currentStep);

    // Next Buttons
    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                currentStep++;
                showStep(currentStep);
                if (currentStep === 3) populateSummary();
            }
        });
    });

    // Previous Buttons
    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            currentStep--;
            showStep(currentStep);
        });
    });

    function showStep(step) {
        steps.forEach(s => {
            s.style.display = 'none';
            s.classList.remove('active');
        });

        const activeContent = wizard.querySelector(`.wizard-step-content[data-step="${step}"]`);
        if (activeContent) {
            activeContent.style.display = 'block';
            // Small timeout to allow display:block to apply before adding class for opacity transition (if needed)
            setTimeout(() => activeContent.classList.add('active'), 10);
        }

        // Update indicators
        indicators.forEach(i => {
            const iStep = parseInt(i.dataset.step);
            if (iStep <= step) {
                i.classList.add('active');
            } else {
                i.classList.remove('active');
            }
        });
    }

    function validateStep(step) {
        const stepContent = wizard.querySelector(`.wizard-step-content[data-step="${step}"]`);
        const inputs = stepContent.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                isValid = false;
                showError(input, 'Campo obbligatorio');
            } else if (input.type === 'email' && input.value && !isValidEmail(input.value)) {
                isValid = false;
                showError(input, 'Email non valida');
            } else if (input.type === 'tel' && input.value && !isValidPhone(input.value)) {
                // Basic phone validation check (length)
                if (input.value.replace(/[^0-9]/g, '').length < 9) {
                    isValid = false;
                    showError(input, 'Numero non valido');
                } else {
                    clearError(input);
                }
            } else {
                clearError(input);
            }
        });

        return isValid;
    }

    function showError(input, message) {
        input.classList.add('error');
        const errorSpan = input.parentNode.querySelector('.error-msg');
        if (errorSpan) errorSpan.textContent = message;
    }

    function clearError(input) {
        input.classList.remove('error');
        const errorSpan = input.parentNode.querySelector('.error-msg');
        if (errorSpan) errorSpan.textContent = '';
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
        return /^[0-9+\s-]{9,20}$/.test(phone);
    }

    function populateSummary() {
        const list = document.getElementById('summary-list');
        list.innerHTML = '';

        const name = document.getElementById('nome').value + ' ' + document.getElementById('cognome').value;
        addItemToSummary('Nome', name);

        addItemToSummary('Email', document.getElementById('email').value);
        addItemToSummary('Telefono', document.getElementById('telefono').value);

        const note = document.getElementById('descrizione').value;
        if (note) addItemToSummary('Note', note);
    }

    function addItemToSummary(label, value) {
        const li = document.createElement('li');
        li.innerHTML = `<strong>${label}:</strong> ${value}`;
        document.getElementById('summary-list').appendChild(li);
    }

    // Form Submission
    wizard.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!validateStep(3)) return;

        const submitBtn = wizard.querySelector('.btn-submit');
        const formMessage = document.getElementById('formMessageWizard');
        const originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Invio in corso...';
        formMessage.textContent = '';
        formMessage.className = 'form-message mt-3';

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Tracking Lead
                fetch('https://dashboard.bbproservice.it/api.php?site_id=4&type=lead')
                    .catch(e => console.error('Tracking error:', e));

                // Success State - Show success message and hide form content
                wizard.innerHTML = `
                    <div class="wizard-success text-center py-5">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
                        <h3>Richiesta Inviata!</h3>
                        <p>${data.message}</p>
                        <p class="text-muted mt-3">Un nostro consulente ti contatterà a breve.</p>
                    </div>
                `;
            } else {
                formMessage.textContent = data.error || 'Si è verificato un errore.';
                formMessage.classList.add('error');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        } catch (error) {
            formMessage.textContent = 'Errore di connessione. Riprova.';
            formMessage.classList.add('error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
});
