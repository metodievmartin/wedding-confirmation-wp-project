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

  // Colour Picker Dropdown
  const dropdownButton = document.getElementById('dropdown-button');
  const dropdownMenu = document.getElementById('color-options');
  const hiddenInput = document.getElementById('selected-color-id');

  if (dropdownButton && dropdownMenu && hiddenInput) {
    dropdownButton.addEventListener('click', function() {
      dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    dropdownMenu.addEventListener('click', function(event) {
      if (event.target.closest('.dropdown-item')) {
        const selectedItem = event.target.closest('.dropdown-item');
        const selectedColourId = selectedItem.getAttribute('data-colour-id');
        const selectedColourHex = selectedItem.getAttribute('data-colour-hex');
        const selectedColourName = selectedItem.getAttribute('data-colour-name');

        dropdownButton.innerHTML = `<span class="color-box" style="background-color: ${selectedColourHex};"></span> ${selectedColourName}`;
        hiddenInput.value = selectedColourId;
        dropdownMenu.style.display = 'none';
      }
    });

    document.addEventListener('click', function(event) {
      if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
      }
    });
  }
});