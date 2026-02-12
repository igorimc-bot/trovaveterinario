// Lead Form Submission Handler
document.addEventListener('DOMContentLoaded', function () {
    const leadForm = document.getElementById('leadForm');
    const formMessage = document.getElementById('formMessage');

    if (leadForm) {
        leadForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Disable submit button
            const submitBtn = leadForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Invio in corso...';

            // Clear previous messages
            formMessage.textContent = '';
            formMessage.className = 'form-message';

            try {
                // Get reCAPTCHA token if available
                let recaptchaToken = '';
                if (typeof grecaptcha !== 'undefined') {
                    recaptchaToken = await grecaptcha.execute(document.querySelector('[name="recaptcha_site_key"]')?.value || '', { action: 'submit_lead' });
                }

                // Prepare form data
                const formData = new FormData(leadForm);
                if (recaptchaToken) {
                    formData.append('recaptcha_token', recaptchaToken);
                }

                // Submit form
                const response = await fetch('/api/submit-lead.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Tracking Lead
                    fetch('https://dashboard.bbproservice.it/api.php?site_id=4&type=lead')
                        .catch(e => console.error('Tracking error:', e));

                    // Success
                    formMessage.textContent = data.message;
                    formMessage.classList.add('success');
                    leadForm.reset();

                    // Scroll to message
                    formMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    // Error
                    formMessage.textContent = data.error || 'Si è verificato un errore. Riprova.';
                    formMessage.classList.add('error');
                }

            } catch (error) {
                formMessage.textContent = 'Errore di connessione. Riprova più tardi.';
                formMessage.classList.add('error');
                console.error('Form submission error:', error);
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }

    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', function () {
            navMenu.classList.toggle('active');
            mobileMenuToggle.classList.toggle('active');
        });
    }

    // FAQ accordion
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', function () {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');

            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // Open clicked item if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    // Hero Slideshow
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length > 0) {
        let currentSlide = 0;

        // Preload images
        slides.forEach(slide => {
            const bg = slide.style.backgroundImage;
            if (bg) {
                const url = bg.match(/url\(['"]?(.*?)['"]?\)/)[1];
                if (url) {
                    const img = new Image();
                    img.src = url;
                }
            }
        });

        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000);
    }
});
