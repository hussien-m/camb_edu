const newsletterForm = document.getElementById('newsletter-form');

if (newsletterForm) {
    newsletterForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const email = document.getElementById('newsletter-email').value;
        const messageEl = document.getElementById('newsletter-message');
        const submitBtn = form.querySelector('button[type="submit"]');

        // Validate email format
        if (!email || !email.includes('@')) {
            messageEl.className = 'text-danger';
            messageEl.textContent = 'Please enter a valid email address.';
            return;
        }

        // Disable button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

        try {
            // Get reCAPTCHA token
            let recaptchaToken = null;
            if (typeof executeRecaptcha === 'function') {
                try {
                    recaptchaToken = await executeRecaptcha('newsletter_subscribe');
                } catch (error) {
                    console.warn('reCAPTCHA not available, continuing without it');
                }
            }

            // Prepare request data
            const requestData = { 
                email: email,
                // Honeypot fields (should be empty for real users)
                website_url: '',
                phone_number_confirm: ''
            };
            
            // Add reCAPTCHA token if available
            if (recaptchaToken) {
                requestData.recaptcha_token = recaptchaToken;
            }

            const response = await fetch(form.getAttribute('data-action'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            });

            const data = await response.json();
            
            messageEl.className = response.ok ? 'text-success' : 'text-danger';
            messageEl.textContent = data.message || 'An error occurred. Please try again.';

            if (response.ok) {
                form.reset();
            }
        } catch (error) {
            console.error('Newsletter error:', error);
            messageEl.className = 'text-danger';
            messageEl.textContent = 'An error occurred. Please try again.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';

            // Clear message after 5 seconds
            setTimeout(() => {
                messageEl.textContent = '';
            }, 5000);
        }
    });
}
