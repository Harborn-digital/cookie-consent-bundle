# Cookie Consent bundle for Symfony
Symfony bundle to append Cookie Consent to your website to comply to AVG/GDPR for cookies.

## Installation
In a Symfony application run this command to install and integrate Cookie Consent bundle in your application:
```bash
composer require connectholland/cookie-consent-bundle
```

Configure your Cookie Consent with the following possible settings
```yaml
ch_cookie_consent:
    theme: 'light' # light, dark
    categories: ['analytics', 'marketing'] # analytics, tracking, marketing, social_media
```
Dark theme:
![alt text](https://raw.githubusercontent.com/ConnectHolland/cookie-consent-bundle/master/doc/dark_theme.png)

Light theme:
![alt text](https://raw.githubusercontent.com/ConnectHolland/cookie-consent-bundle/master/doc/light_theme.png)

## Usage
After submitting the Cookie Consent form the cookie **Cookie_Consent** is saved with the date of submit and for each configured category a cookie saved e.g. **Cookie_Category_analytics** with it's value set to either *true* or *false*

#TwigExtension
You can use the TwigExtension to check if user has given it's permission for certain cookie categories
```twig
{% if chcookieconsent_isAllowed('analytics') == true %}
    ...
{% endif %}
```

## Customization
### Translations
All texts can be altered via Symfony translations by overwriting the CHCookieConsentBundle translation files

### Styling
CHCookieConsentBundle comes with a default styling which can be used by including either the css or scss file in Resources/assets/css/.
