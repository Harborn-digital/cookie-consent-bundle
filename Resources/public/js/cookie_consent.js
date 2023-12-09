document.addEventListener("DOMContentLoaded", function () {
    const cookieConsent = document.querySelector('.cookie-consent');
    const cookieConsentForm = document.querySelector('.cookie-consent__form');
    const cookieConsentFormBtn = document.querySelectorAll('.js-submit-cookie-consent-form');

    const cookieConsentDialog = document.querySelector('.cookie-consent-dialog');
    if (cookieConsentDialog) {
        cookieConsentDialog.showModal();

        const saveButton = cookieConsentDialog.querySelector('#cookie_consent_save');
        if (saveButton) {
            saveButton.addEventListener('click', function () {
                cookieConsentDialog.close();
            });
        }

        cookieConsentDialog.querySelectorAll('.js-modal-close').forEach(function (closeButton) {
            closeButton.addEventListener('click', function () {
                cookieConsentDialog.close();
            });
        });
    }

    if (cookieConsentForm) {
        // Submit form via ajax
        cookieConsentFormBtn.forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();

                const formAction = cookieConsentForm.action ? cookieConsentForm.action : location.href;
                const xhr = new XMLHttpRequest();

                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {

                        if (cookieConsentDialog) {
                            cookieConsentDialog.close();
                        } else {
                            cookieConsent.remove();
                        }

                        const formSubmittedEvent = new CustomEvent('cookie-consent-form-submit-successful', {
                            detail: event.target
                        });
                        document.dispatchEvent(formSubmittedEvent);
                    }
                };
                xhr.open('POST', formAction);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(serializeForm(cookieConsentForm, event.target));

            }, false);
        });
    }
});

function serializeForm(form, clickedButton) {
    const serialized = [];

    Array.from(form.elements).forEach(function (field) {
        if ((field.type !== 'checkbox' && field.type !== 'radio' && field.type !== 'button') || field.checked) {
            serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
        }
    });

    serialized.push(encodeURIComponent(clickedButton.getAttribute('name')) + "=");

    return serialized.join('&');
}