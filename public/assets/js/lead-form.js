document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('leadFormWizard');
    if (!form) return;

    // Autocomplete per Comune
    const comuneSearch = document.getElementById('comune_search');
    const comuneResults = document.getElementById('comune_results');
    const comuneIdHidden = document.getElementById('comune_id_hidden');
    const provinciaIdHidden = document.getElementById('provincia_id_hidden');
    const regioneIdHidden = document.getElementById('regione_id_hidden');
    let timeoutId;

    if (comuneSearch && !comuneSearch.hasAttribute('disabled') && !comuneSearch.hasAttribute('readonly')) {
        comuneSearch.addEventListener('input', function () {
            clearTimeout(timeoutId);
            const query = this.value.trim();

            // Clear hidden fields when typing
            if (comuneIdHidden) comuneIdHidden.value = '';
            if (provinciaIdHidden) provinciaIdHidden.value = '';
            if (regioneIdHidden) regioneIdHidden.value = '';

            if (query.length < 2) {
                comuneResults.style.display = 'none';
                return;
            }

            timeoutId = setTimeout(() => {
                fetch(`/api/search-comuni.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        comuneResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item.text;
                                li.addEventListener('click', () => {
                                    comuneSearch.value = item.text;
                                    if (comuneIdHidden) comuneIdHidden.value = item.id;
                                    if (provinciaIdHidden) provinciaIdHidden.value = item.provincia_id;
                                    if (regioneIdHidden) regioneIdHidden.value = item.regione_id;
                                    comuneResults.style.display = 'none';
                                });
                                comuneResults.appendChild(li);
                            });
                            comuneResults.style.display = 'block';
                        } else {
                            comuneResults.style.display = 'none';
                        }
                    })
                    .catch(err => console.error('Errore ricerca comuni:', err));
            }, 300);
        });

        // Hide autocomplete when clicking outside
        document.addEventListener('click', function (e) {
            if (!comuneSearch.contains(e.target) && !comuneResults.contains(e.target)) {
                comuneResults.style.display = 'none';
            }
        });
    }

    // Validazione base
    function validateForm() {
        const inputs = form.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && input.type !== 'checkbox' && !input.value.trim()) {
                isValid = false;
                showError(input, 'Campo obbligatorio');
            } else if (input.type === 'checkbox' && input.hasAttribute('required') && !input.checked) {
                isValid = false;
                showError(input, 'Devi accettare per proseguire');
            } else if (input.type === 'email' && input.value && !isValidEmail(input.value)) {
                isValid = false;
                showError(input, 'Email non valida');
            } else if (input.type === 'tel' && input.value && !isValidPhone(input.value)) {
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
        const parent = input.closest('.form-group') || input.parentNode;
        const errorSpan = parent.querySelector('.error-msg');
        if (errorSpan) errorSpan.textContent = message;
    }

    function clearError(input) {
        input.classList.remove('error');
        const parent = input.closest('.form-group') || input.parentNode;
        const errorSpan = parent.querySelector('.error-msg');
        if (errorSpan) errorSpan.textContent = '';
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
        return /^[0-9+\s-]{9,20}$/.test(phone);
    }

    // Form Submission
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!validateForm()) return;

        const submitBtn = form.querySelector('.btn-submit');
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
                fetch('https://dashboard.bbproservice.it/api.php?site_id=7&type=lead')
                    .catch(e => console.error('Tracking error:', e));

                // Success State - Show success message
                form.innerHTML = `
                    <div class="form-success text-center py-5">
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
