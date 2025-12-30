// Contact Form Script
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('contactAlert');
    const alertMessage = document.getElementById('contactAlertMessage');

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Verifying...';

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        alertBox.style.display = 'none';

        // Get reCAPTCHA token if available
        let recaptchaToken = null;
        if (typeof executeRecaptcha === 'function') {
            try {
                recaptchaToken = await executeRecaptcha('contact_form');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';
            } catch (error) {
                console.warn('reCAPTCHA not available, continuing without it');
            }
        }

        // Get form data
        const formData = new FormData(form);
        
        // Add reCAPTCHA token if available
        if (recaptchaToken) {
            formData.append('recaptcha_token', recaptchaToken);
        }

        // Send Ajax request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw { status: response.status, data: err };
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Success message
                alertBox.className = 'alert alert-success alert-dismissible fade show';
                alertMessage.textContent = data.message || 'Your message has been sent successfully!';
                alertBox.style.display = 'block';

                // Reset form
                form.reset();

                // Scroll to alert
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // Error message
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertMessage.textContent = data.message || 'Something went wrong. Please try again.';
                alertBox.style.display = 'block';
            }
        })
        .catch(error => {
            if (error.status === 422 && error.data.errors) {
                // Validation errors
                const errors = error.data.errors;
                for (const field in errors) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.parentElement.querySelector('.invalid-feedback:last-child');
                        if (feedback) {
                            feedback.textContent = errors[field][0];
                            feedback.style.display = 'block';
                        }
                    }
                }

                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertMessage.textContent = 'Please fix the errors below.';
                alertBox.style.display = 'block';
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // General error
                alertBox.className = 'alert alert-danger alert-dismissible fade show';
                alertMessage.textContent = 'An error occurred. Please try again later.';
                alertBox.style.display = 'block';
            }
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Send Message';
        });
    });
});
