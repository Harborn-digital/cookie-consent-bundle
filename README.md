# Cookie Consent bundle for Symfony
Symfony bundle to append Cookie Consent to your website.

## Installation
In a Symfony application run this command to install and integrate Cookie Consent bundle in your application:
```bash
composer require connectholland/cookie-consent-bundle
```

Configure your Cookie Consent with the following possible settings
```yaml
ch_cookie_consent:
    theme: 'light' # choices between 'light' or 'dark'
    categories: ['analytics', 'marketing'] # Default cookie categories
```
## Usage
You can use the TwigExtension to check if user has given it's permission for certain cookie categories
```twig
{% if chcookieconsent_isCategoryPermitted('analytics') == true %}
    ...
{% endif %}
```

Dark theme:
![alt text](https://raw.githubusercontent.com/connectholland/cookie-consent-bundle/master/doc/dark_theme.png)

Light theme:
![alt text](https://raw.githubusercontent.com/connectholland/cookie-consent-bundle/master/doc/light_theme.png)

## Customization
### Translations
All texts can be altered via Symfony translations by overwriting the CHCookieConsentBundle translation files

### Styling
CHCookieConsentBundle comes with a default styling which can be used by including either the css or scss file in Resources/assets/css/.
