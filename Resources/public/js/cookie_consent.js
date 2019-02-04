document.addEventListener("DOMContentLoaded", function() {
    var cookieConsent = document.getElementsByClassName('ch-cookie-consent')[0];

    if (cookieConsent) {
        document.body.style.marginTop = cookieConsent.offsetHeight + 'px';
    }
});
