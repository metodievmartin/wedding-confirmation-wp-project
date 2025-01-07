import $ from 'jquery';

export function init(contactFormSelector = '#contact-form') {
  $(contactFormSelector).on('submit', async function (e) {
    e.preventDefault();

    // TODO: implement recaptcha

    const $contactForm = $(this);
    const $formContainer = $contactForm.closest('.contact-form-container');
    const $submissionAlert = $formContainer.find('.form-submission-alert');
    const $submitButton = $contactForm.find('#form-submit-button');

    $submissionAlert.addClass('d-none');
    $submissionAlert.removeClass('alert-error alert-success');

    if (!this.checkValidity()) {
      $contactForm.addClass('was-validated');
      return;
    }

    $submitButton.prop('disabled', true);

    // Create form data object
    const formData = {
      sender_name: $contactForm.find('#sender_name').val().trim(),
      sender_email: $contactForm.find('#sender_email').val().trim(),
      additional_info: $contactForm.find('#additional_info').val().trim(),
    };

    try {
      const response = await fetch(
        `${theme_data.root_url}/wp-json/confirmation/v1/submit-form`,
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': theme_data.nonce,
          },
          body: JSON.stringify(formData),
        }
      );

      const result = await response.json();

      if (response.ok && result.success) {
        $contactForm.removeClass('was-validated');
        $contactForm[0].reset();
        $submissionAlert.addClass('alert-success');
        console.log(result);
      } else {
        $submissionAlert.addClass('alert-danger');
      }
    } catch (error) {
      console.error('Submission failed:', error);
      $submissionAlert.addClass('alert-danger');
    }

    $submissionAlert.removeClass('d-none');
    $submitButton.prop('disabled', false);
  });
}
