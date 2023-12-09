<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Cookie;

use DateInterval;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CookieHandler
{
    private CookieSettings $cookieSettings;

    public function __construct(array $cookieSettings)
    {
        $this->cookieSettings = $this->castConfigToCookieSettings($cookieSettings);
    }

    private function castConfigToCookieSettings(array $config): CookieSettings
    {
        return new CookieSettings(
            $config['name_prefix'],
            $this->castCookieConfigToCookieSetting($config['cookies']['consent_cookie']),
            $this->castCookieConfigToCookieSetting($config['cookies']['consent_key_cookie']),
            $this->castCookieConfigToCookieSetting($config['cookies']['consent_categories_cookie']),
        );
    }

    private function castCookieConfigToCookieSetting(array $config): CookieSetting
    {
        return new CookieSetting(
            $config['name'],
            $config['http_only'],
            $config['secure'],
            $config['same_site'],
            $config['expires'],
        );
    }

    /**
     * Save chosen cookie categories in cookies.
     * @throws Exception
     */
    public function save(array $categories, string $cookieConsentKey, Response $response): void
    {
        $consentCookie = $this->cookieSettings->getConsentCookie();
        if ($consentCookie != null) {
            $this->saveCookie($consentCookie->getName(), date('r'), $consentCookie->getExpires(),
                $consentCookie->isSecure(), $consentCookie->isHttpOnly(), $consentCookie->getSameSite(), $response);
        }

        $consentKeyCookie = $this->cookieSettings->getConsentKeyCookie();
        if ($consentKeyCookie != null) {
            $this->saveCookie($consentKeyCookie->getName(), $cookieConsentKey, $consentKeyCookie->getExpires(),
                $consentKeyCookie->isSecure(), $consentKeyCookie->isHttpOnly(), $consentKeyCookie->getSameSite(), $response);
        }

        $consentCategoriesCookie = $this->cookieSettings->getConsentCategoriesCookie();
        if ($consentCategoriesCookie != null) {
            foreach ($categories as $category => $permitted) {
                $this->saveCookie($consentCategoriesCookie->getName() . '-' . $category, $permitted, $consentCategoriesCookie->getExpires(),
                    $consentCategoriesCookie->isSecure(), $consentCategoriesCookie->isHttpOnly(), $consentCategoriesCookie->getSameSite(), $response);
            }
        }
    }

    /**
     * Add cookie to response headers.
     * @param string $name
     * @param string $value
     * @param string $expires
     * @param bool $secure
     * @param bool $httpOnly
     * @param string $sameSite
     * @param Response $response
     * @throws Exception
     */
    protected function saveCookie(string   $name,
                                  string   $value,
                                  string   $expires,
                                  bool     $secure,
                                  bool     $httpOnly,
                                  string   $sameSite,
                                  Response $response): void
    {
        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval($expires));

        if ($sameSite == 'none') {
            $secure = true;
        }

        $response->headers->setCookie(
            new Cookie($this->cookieSettings->getNamePrefix() . $name, $value, $expirationDate, '/', null, $secure, $httpOnly, true, $sameSite)
        );
    }
}
