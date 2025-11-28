const newsletterForm = document.getElementById('newsletter-form');

if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(form.getAttribute('data-action'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json().then(data => ({ status: response.status, data: data })))
        .then(({ status, data }) => {
            messageEl.className = status === 200 || status === 201 ? 'text-success' : 'text-danger';
            messageEl.textContent = data.message || 'An error occurred. Please try again.';

            if (status === 200 || status === 201) {
                form.reset();
            }
        })
        .catch(error => {
            messageEl.className = 'text-danger';
            messageEl.textContent = 'An error occurred. Please try again.';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';

            // Clear message after 5 seconds
            setTimeout(() => {
                messageEl.textContent = '';
            }, 5000);
        });
    });
}
