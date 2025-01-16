document.addEventListener('DOMContentLoaded', function() {
  console.log('Ready!');

  const saveSettingsForm = document.getElementById('save-settings-form'); // Select the form element
  const dateInput = document.getElementById('wccf_date_time_picker'); // Local datetime input
  const utcInput = document.getElementById('wccf_date_time_utc'); // Hidden input for full datetime with timezone
  const timezoneInput = document.getElementById('wccf_date_time_timezone'); // Hidden input for timezone

  if (saveSettingsForm && dateInput && utcInput && timezoneInput) {
    saveSettingsForm.addEventListener('submit', function(event) {
      // Ensure the local date input has a value
      if (dateInput.value) {
        const localDate = new Date(dateInput.value);

        // Set the UTC equivalent of the selected date
        utcInput.value = localDate.toISOString();
        console.log('UTC Date:', utcInput.value);

        // Set the user's timezone
        timezoneInput.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
        console.log('Timezone:', timezoneInput.value);
      }
    });
  }
});