document.addEventListener("DOMContentLoaded", function() {
    var cookieConsent = document.getElementsByClassName('ch-cookie-consent')[0];
    var cookieConsentForm = document.getElementsByClassName('ch-cookie-consent__form')[0];
    var cookieConsentFormBtn = document.getElementsByClassName('ch-cookie-consent__btn')[0];

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
        cookieConsentFormBtn.addEventListener('click', function (event) {
            event.preventDefault();

            var xhr = new XMLHttpRequest();
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    cookieConsent.style.display = 'none';
                }
            };
            xhr.open('POST', cookieConsentForm.action);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(serializeForm(cookieConsentForm));
        }, false);
    }
});

function serializeForm(form) {
	var serialized = [];

	for (var i = 0; i < form.elements.length; i++) {
        var field = form.elements[i];

        if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
            serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
        }
    }

    return serialized.join('&');
}
