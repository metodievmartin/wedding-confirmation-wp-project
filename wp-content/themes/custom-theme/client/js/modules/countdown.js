import $ from 'jquery';

export function init(countdownSelector = '#wccf-countdown') {
  // Time constants
  const SECOND = 1000; // Milliseconds in a second
  const MINUTE = 60 * SECOND; // Milliseconds in a minute
  const HOUR = 60 * MINUTE; // Milliseconds in an hour
  const DAY = 24 * HOUR; // Milliseconds in a day

  const $countdown = $(countdownSelector);
  const targetDateString = $countdown.data('end-date');
  const $daysContainer = $countdown.find('#cd-days');
  const $hoursContainer = $countdown.find('#cd-hours');
  const $minutesContainer = $countdown.find('#cd-minutes');
  const $secondsContainer = $countdown.find('#cd-seconds');

  if (!targetDateString) {
    $daysContainer.text('00');
    $hoursContainer.text('00');
    $minutesContainer.text('00');
    $secondsContainer.text('00');
    return;
  }

  const targetDate = new Date(targetDateString);

  function updateCountdown() {
    const now = new Date();
    const timeRemaining = targetDate - now;

    if (timeRemaining <= 0) {
      clearInterval(countdownInterval);

      $daysContainer.text('00');
      $hoursContainer.text('00');
      $minutesContainer.text('00');
      $secondsContainer.text('00');

      return;
    }

    const days = Math.floor(timeRemaining / DAY);
    const hours = Math.floor((timeRemaining % DAY) / HOUR);
    const minutes = Math.floor((timeRemaining % HOUR) / MINUTE);
    const seconds = Math.floor((timeRemaining % MINUTE) / SECOND);

    $daysContainer.text(String(days).padStart(2, '0'));
    $hoursContainer.text(String(hours).padStart(2, '0'));
    $minutesContainer.text(String(minutes).padStart(2, '0'));
    $secondsContainer.text(String(seconds).padStart(2, '0'));
  }

  // Start the countdown
  const countdownInterval = setInterval(updateCountdown, 1000);

  // Initial call to display immediately
  updateCountdown();
}
