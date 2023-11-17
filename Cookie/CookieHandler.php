<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Cookie;

use ConnectHolland\CookieConsentBundle\Enum\CookieNameEnum;
use DateInterval;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CookieHandler
{
//    private bool $httpOnly;
//    private bool $secure;
//    private null|string $sameSite;
//    private string $expires;
    private array $cookies;

    public function __construct(array $cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * Save chosen cookie categories in cookies.
     * @throws Exception
     */
    public function save(array $categories, string $cookieConsentKey, Response $response): void
    {
        if (isset($this->cookies['consent'])) {
            $consentCookieSettings = $this->cookies['consent'];
            $this->saveCookie(CookieNameEnum::COOKIE_CONSENT_NAME, date('r'), $consentCookieSettings['expires'],
                $consentCookieSettings['secure'], $consentCookieSettings['http_only'], $consentCookieSettings['same_site'], $response);
        }
        if (isset($this->cookies['consent_key'])) {
            $consentKeyCookieSettings = $this->cookies['consent_key'];
            $this->saveCookie(CookieNameEnum::COOKIE_CONSENT_KEY_NAME, $cookieConsentKey,
                $consentKeyCookieSettings['expires'], $consentKeyCookieSettings['secure'], $consentKeyCookieSettings['http_only'],
                $consentKeyCookieSettings['same_site'], $response);
        }

        if (isset($this->cookies['consent_categories'])) {
            $categoryCookieSettings = $this->cookies['consent_categories'];
            foreach ($categories as $category => $permitted) {
                $this->saveCookie(CookieNameEnum::getCookieCategoryName($category), $permitted, $categoryCookieSettings['expires'],
                    $categoryCookieSettings['secure'], $categoryCookieSettings['http_only'], $categoryCookieSettings['same_site'], $response);
            }
        }
    }

    /**
     * Add cookie to response headers.
     * @param string $sameSite
     * @param bool $httpOnly
     * @param string $expires
     * @param bool $secure
     * @throws Exception
     */
    protected function saveCookie(string $name, string $value, string $expires, bool $secure, bool $httpOnly, string $sameSite, Response $response): void
    {
        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval($expires));

        $response->headers->setCookie(
            new Cookie($name, $value, $expirationDate, '/', null, $secure, $httpOnly, true, $sameSite)
        );
    }
}
