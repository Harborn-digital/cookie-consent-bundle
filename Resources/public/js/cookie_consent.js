document.addEventListener("DOMContentLoaded", function() {
    var cookieConsent = document.querySelector('.cookie-consent');
    var cookieConsentForm = document.querySelector('.cookie-consent__form');
    var cookieConsentFormBtn = document.querySelectorAll('.cookie-consent__btn');
    var cookieConsentCategoryDetails = document.querySelector('.cookie-consent__category-group');
    var cookieConsentCategoryDetailsToggle = document.querySelector('.cookie-consent__toggle-details');

    var cookieConsentDialog = document.querySelector('.cookie-consent-dialog');
    if (cookieConsentDialog) {
        cookieConsentDialog.showModal();

        var saveButton = cookieConsentDialog.querySelector('#cookie_consent_save');
        if (saveButton) {
            saveButton.addEventListener('click', function () {
                cookieConsentDialog.close();
            });
        }
    }

    if (cookieConsentForm) {
        // Submit form via ajax
        for (var i = 0; i < cookieConsentFormBtn.length; i++) {
            var btn = cookieConsentFormBtn[i];
            btn.addEventListener('click', function (event) {
                event.preventDefault();

                var formAction = cookieConsentForm.action ? cookieConsentForm.action : location.href;
                var xhr = new XMLHttpRequest();

                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        cookieConsent.style.display = 'none';
                        var buttonEvent = new CustomEvent('cookie-consent-form-submit-successful', {
                            detail: event.target
                        });
                        document.dispatchEvent(buttonEvent);
                    }
                };
                xhr.open('POST', formAction);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(serializeForm(cookieConsentForm, event.target));

            }, false);
        }
    }

    if (cookieConsentCategoryDetails && cookieConsentCategoryDetailsToggle) {
        cookieConsentCategoryDetailsToggle.addEventListener('click', function() {
            var detailsIsHidden = cookieConsentCategoryDetails.style.display !== 'block';
            cookieConsentCategoryDetails.style.display = detailsIsHidden ? 'block' : 'none';
            cookieConsentCategoryDetailsToggle.querySelector('.cookie-consent__toggle-details-hide').style.display = detailsIsHidden ? 'block' : 'none';
            cookieConsentCategoryDetailsToggle.querySelector('.cookie-consent__toggle-details-show').style.display = detailsIsHidden ? 'none' : 'block';
        });
    }
});

function serializeForm(form, clickedButton) {
    var serialized = [];

    for (var i = 0; i < form.elements.length; i++) {
        var field = form.elements[i];

        if ((field.type !== 'checkbox' && field.type !== 'radio' && field.type !== 'button') || field.checked) {
            serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
        }
    }

    serialized.push(encodeURIComponent(clickedButton.getAttribute('name')) + "=");

    return serialized.join('&');
}