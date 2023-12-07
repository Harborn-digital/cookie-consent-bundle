# Cookie Consent bundle for Symfony
Symfony bundle to integrate a cookie consent dialog to your website and to handle cookies according to AVG/GDPR.

## Installation

### Step 1: Download using composer
In a Symfony application run this command to install and integrate Cookie Consent bundle in your application:
```bash
composer require huppys/cookie-consent-bundle
```

### Step 2: Enable the bundle
When not using Symfony Flex, enable the bundle manually:

In AppKernel.php add the following line to the registerBundles() method:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new ConnectHolland\CookieConsentBundle\CookieConsentBundle(),
        // ...
    );
}
```
or in config/bundles.php add the following line to the array:
```php
<?php

return [
    // ...
    huppys\CookieConsentBundle\CookieConsentBundle::class => ['all' => true],
    // ...
];
```


### Step 3: Enable the routing
When not using Symfony Flex, enable the bundles routing manually by adding the following lines to your config/routing.yml:
```yaml
cookie_consent:
    resource: "@CookieConsentBundle/Resources/config/routing.yaml"
```

### Step 4: Configure to your needs
Configure the cookie consent bundle to your needs. By default, the most secure options are enabled.
You can change the config in config/packages/cookie_consent.yaml:
```yaml
cookie_consent:
    consent_categories: # Below are the default supported categories
        - 'analytics'
        - 'tracking'
        - 'marketing'
        - 'social_media'
    cookie_settings:
      name_prefix: '' # string, any string you like to prefix the cookie names with
      cookie_settings:
        consent_cookie:
          httpOnly: true # boolean, refer to mdn docs for more info
          secure: true # boolean, enable or disable transport only over https
          same_site: 'lax' # available values: 'strict', 'lax', 'none'
          expires: 'P180D' # available values: PHP formatted date string, 'P180D' (180 days), 'P1Y' (1 year) etc.
        consent_key_cookie:
          httpOnly: true
          secure: true
          same_site: 'lax'
          expires: 'P180D'
        consent_categories_cookie:
          httpOnly: true
          secure: true
          same_site: 'lax'
          expires: 'P180D'
    theme: 'light' # available values: 'light', 'dark'
    use_logger: true # boolean; logs user actions to database
    position: 'top' # available values: 'top', 'bottom', 'dialog'
    form_action: $routeName # When set, xhr-Requests will only be sent to this route. Take care of having the route available.
    csrf_protection: true # boolean; enable or disable csrf protection for the form
```

## Usage
### Twig implementation
Load the cookie consent in Twig via render_esi ( to prevent caching ) at any place you like:
```twig
{{ render_esi(path('cookie_consent.show')) }}
{{ render_esi(path('cookie_consent.show_if_cookie_consent_not_set')) }}
```

If you want to load the cookie consent with a specific locale you can pass the locale as a parameter:
```twig
{{ render_esi(path('cookie_consent.show', { 'locale' : 'en' })) }}
{{ render_esi(path('cookie_consent.show_if_cookie_consent_not_set', { 'locale' : app.request.locale })) }}
```

### Cookies
When a user submits the form the preferences are saved as cookies. The cookies have a lifetime of 1 year. The following cookies are saved:
- **Cookie_Consent**: date of submit
- **Cookie_Consent_Key**: Generated key as identifier to the submitted Cookie Consent of the user
- **Cookie_Category_[CATEGORY]**: selected value of user (*true* or *false*)

### Logging
AVG/GDPR requires all given cookie preferences of users to be explainable by the webmasters. For this we log all cookie preferences to the database. IP addresses are anonymized. This option can be disabled in the config.

![Database logging](https://raw.githubusercontent.com/ConnectHolland/cookie-consent-bundle/master/Resources/doc/log.png)

### Themes
![Dark Theme](https://raw.githubusercontent.com/ConnectHolland/cookie-consent-bundle/master/Resources/doc/dark_theme.png)
![Light Theme](https://raw.githubusercontent.com/ConnectHolland/cookie-consent-bundle/master/Resources/doc/light_theme.png)

### TwigExtension
The following TwigExtension functions are available:

**cookieconsent_isCategoryAllowedByUser**
check if user has given it's permission for certain cookie categories
```twig
{% if cookieconsent_isCategoryAllowedByUser('analytics') == true %}
    ...
{% endif %}
```

**cookieconsent_isCookieConsentSavedByUser**
check if user has saved any cookie preferences
```twig
{% if cookieconsent_isCookieConsentSavedByUser() == true %}
    ...
{% endif %}
```

## Customization
### Categories
You can add or remove any category by changing the config and making sure there are translations available for these categories.

### Translations
All texts can be altered via Symfony translations by overwriting the CookieConsentBundle translation files.

### Styling
CookieConsentBundle comes with a default styling. A sass file is available in Resources/assets/css/cookie_consent.scss and a build css file is available in Resources/public/css/cookie_consent.css. Colors can easily be adjusted by setting the variables available in the sass file.

To install these assets run:
```bash
bin/console assets:install
```

And include the styling in your template:
```twig
{% include "@CookieConsent/cookie_consent_styling.html.twig" %}
```

### Javascript
By loading Resources/public/js/cookie_consent.js the cookie consent will be submitted via ajax and the cookie consent will be shown on top of your website while pushing down the rest of the website.

### Events
When a form button is clicked, the event of cookie-consent-form-submit-successful is created. Use the following code to listen to the event and add your custom functionality.
```javascript
document.addEventListener('cookie-consent-form-submit-successful', function (e) {
    // ... your functionality
    // ... e.detail is available to see which button is clicked.
}, false);
```

### Template Themes
You can override the templates by placing templates inside your project (except for Symfony 5 projects):

```twig
# app/Resources/CookieConsentBundle/views/cookie_consent.html.twig
{% extends '@!CookieConsent/cookie_consent.html.twig' %}

{% block title %}
    Your custom title
{% endblock %}
```
