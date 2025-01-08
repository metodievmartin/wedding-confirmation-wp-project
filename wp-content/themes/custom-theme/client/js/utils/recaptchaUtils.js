export function getRecaptchaOptions() {
  return window.recaptchaGlobalOptions || { isEnabled: false, siteKey: '' };
}
