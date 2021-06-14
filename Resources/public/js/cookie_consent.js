document.addEventListener("DOMContentLoaded", function() {
    var cookieConsent = document.querySelector('.ch-cookie-consent');
    var cookieConsentForm = document.querySelector('.ch-cookie-consent__form');
    var cookieConsentFormBtn = document.querySelectorAll('.ch-cookie-consent__btn');
    var cookieConsentCategoryDetails = document.querySelector('.ch-cookie-consent__category-group');
    var cookieConsentCategoryDetailsToggle = document.querySelector('.ch-cookie-consent__toggle-details');

    // If cookie consent is direct child of body, assume it should be placed on top of the site pushing down the rest of the website
    if (cookieConsent && cookieConsent.parentNode.nodeName === 'BODY') {
        if (cookieConsent.classList.contains('ch-cookie-consent--top')) {
            document.body.style.marginTop = cookieConsent.offsetHeight + 'px';

            cookieConsent.style.position = 'absolute';
            cookieConsent.style.top = '0';
            cookieConsent.style.left = '0';
        } else {
            document.body.style.marginBottom = cookieConsent.offsetHeight + 'px';

            cookieConsent.style.position = 'fixed';
            cookieConsent.style.bottom = '0';
            cookieConsent.style.left = '0';
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

                // Clear all styles from body to prevent the white margin at the end of the page
                document.body.style.marginBottom = null;
                document.body.style.marginTop  = null;
            }, false);
        }
    }

    if (cookieConsentCategoryDetails && cookieConsentCategoryDetailsToggle) {
        cookieConsentCategoryDetailsToggle.addEventListener('click', function() {
            var detailsIsHidden = cookieConsentCategoryDetails.style.display !== 'block';
            cookieConsentCategoryDetails.style.display = detailsIsHidden ? 'block' : 'none';
            cookieConsentCategoryDetailsToggle.querySelector('.ch-cookie-consent__toggle-details-hide').style.display = detailsIsHidden ? 'block' : 'none';
            cookieConsentCategoryDetailsToggle.querySelector('.ch-cookie-consent__toggle-details-show').style.display = detailsIsHidden ? 'none' : 'block';
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

if ( typeof window.CustomEvent !== "function" ) {
    function CustomEvent(event, params) {
        params = params || {bubbles: false, cancelable: false, detail: undefined};
        var evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    }

    CustomEvent.prototype = window.Event.prototype;

    window.CustomEvent = CustomEvent;
}
