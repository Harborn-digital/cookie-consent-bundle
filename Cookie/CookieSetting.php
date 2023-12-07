<?php

namespace huppys\CookieConsentBundle\Cookie;

class CookieSetting
{
    private string $name;
    private bool $httpOnly;
    private bool $secure;
    private string $sameSite;
    private string $expires;

    public function __construct(string $name, bool $httpOnly, bool $secure, string $sameSite, string $expires)
    {
        $this->name = $name;
        $this->httpOnly = $httpOnly;
        $this->secure = $secure;
        $this->sameSite = $sameSite;
        $this->expires = $expires;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function getSameSite(): string
    {
        return $this->sameSite;
    }

    public function getExpires(): string
    {
        return $this->expires;
    }
}