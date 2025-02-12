import $ from 'jquery';

export function init() {
  $('.form-submission-alert').on('click', '.btn-close', function () {
    $(this).closest('.form-submission-alert').addClass('d-none');
  });
}
