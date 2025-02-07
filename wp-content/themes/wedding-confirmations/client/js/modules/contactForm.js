import $ from 'jquery';
import { getRecaptchaOptions } from '../utils/recaptchaUtils';

export function init(contactFormSelector = '#contact-form') {
  const recaptchaOptions = getRecaptchaOptions();
  let $contactForm;
  let $formContainer;
  let $submissionAlert;
  let $recaptchaAction;
  let $recaptchaToken;
  let $submitButton;

  $(contactFormSelector).on('submit', async function (e) {
    e.preventDefault();

    $contactForm = $(this);
    $formContainer = $contactForm.closest('.contact-form-container');
    $submissionAlert = $formContainer.find('.form-submission-alert');
    $submitButton = $contactForm.find('#form-submit-button');
    $recaptchaAction = $formContainer.find('input[name="recaptcha_action"]');
    $recaptchaToken = $formContainer.find('input[name="recaptcha_token"]');

    $submissionAlert.addClass('d-none');
    $submissionAlert.removeClass('alert-error alert-success');

    if (!this.checkValidity()) {
      $contactForm.addClass('was-validated');
      return;
    }

    $submitButton.prop('disabled', true);

    if (recaptchaOptions.isEnabled) {
      grecaptcha.ready(function () {
        grecaptcha
          .execute(recaptchaOptions.siteKey, { action: $recaptchaAction.val() })
          .then(function (token) {
            $recaptchaToken.val(token);
            console.log('recaptcha token: ', token);
            makeRequest();
          });
      });

      return;
    }

    makeRequest();
  });

  async function makeRequest() {
    // Create form data object
    const formData = {
      guest_first_name: $contactForm.find('#guest_first_name').val()?.trim(),
      guest_last_name: $contactForm.find('#guest_last_name').val()?.trim(),
      num_guests: $contactForm.find('#num_guests').val()?.trim(),
      guest_email: $contactForm.find('#guest_email').val()?.trim(),
      rsvp_confirmation: $contactForm.find('#rsvp_confirmation').val()?.trim(),
      additional_info: $contactForm.find('#additional_info').val()?.trim(),
      recaptcha_action: $recaptchaAction.val(),
      recaptcha_token: $recaptchaToken.val(),
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
  }
}
