<?php

namespace huppys\CookieConsentBundle\Cookie;

class CookieSettings
{
    private string $namePrefix;
    private CookieSetting $consentCookie;
    private CookieSetting $consentKeyCookie;
    private CookieSetting $consentCategoriesCookie;

    public function __construct(string $namePrefix, CookieSetting $consentCookie, CookieSetting $consentKeyCookie, CookieSetting $consentCategoriesCookie)
    {
        $this->namePrefix = $namePrefix;
        $this->consentCookie = $consentCookie;
        $this->consentKeyCookie = $consentKeyCookie;
        $this->consentCategoriesCookie = $consentCategoriesCookie;
    }

    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }

    public function getConsentCookie(): CookieSetting
    {
        return $this->consentCookie;
    }

    public function getConsentKeyCookie(): CookieSetting
    {
        return $this->consentKeyCookie;
    }

    public function getConsentCategoriesCookie(): CookieSetting
    {
        return $this->consentCategoriesCookie;
    }
}