// If cookie consent is direct child of body, assume it should be placed on top of the site pushing down the rest of the website
document.addEventListener("DOMContentLoaded", function() {
    var cookieConsent = document.getElementsByClassName('ch-cookie-consent')[0];

    if (cookieConsent && cookieConsent.parentNode.nodeName === 'BODY') {
        document.body.style.marginTop = cookieConsent.offsetHeight + 'px';

        cookieConsent.style.position = 'absolute';
        cookieConsent.style.top = '0';
        cookieConsent.style.left = '0';
    }
});
