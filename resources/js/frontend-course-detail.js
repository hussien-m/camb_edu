$(document).ready(function() {
    $('#inquiryForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#inquiry-alert').hide();

        // Disable submit button
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <span>Sending...</span>');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Show success message
                $('#inquiry-alert')
                    .removeClass('alert-danger')
                    .addClass('alert alert-success')
                    .html('<i class="fas fa-check-circle me-2"></i> Thank you for your inquiry! We will contact you soon.')
                    .slideDown();

                // Reset form
                $('#inquiryForm')[0].reset();

                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);

                // Hide success message after 5 seconds
                setTimeout(function() {
                    $('#inquiry-alert').slideUp();
                }, 5000);

                // Scroll to alert
                $('html, body').animate({
                    scrollTop: $('#inquiry-alert').offset().top - 100
                }, 500);
            },
            error: function(xhr) {
                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {
                        const input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(messages[0]);
                    });

                    // Show error alert
                    $('#inquiry-alert')
                        .removeClass('alert-success')
                        .addClass('alert alert-danger')
                        .html('<i class="fas fa-exclamation-circle me-2"></i> Please fix the errors below.')
                        .slideDown();
                } else {
                    // General error
                    $('#inquiry-alert')
                        .removeClass('alert-success')
                        .addClass('alert alert-danger')
                        .html('<i class="fas fa-exclamation-circle me-2"></i> Something went wrong. Please try again.')
                        .slideDown();
                }

                // Scroll to alert
                $('html, body').animate({
                    scrollTop: $('#inquiry-alert').offset().top - 100
                }, 500);
            }
        });
    });
});
