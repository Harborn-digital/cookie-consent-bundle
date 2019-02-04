<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Cookie;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CookieHandler
{
    const COOKIE_CONSENT_NAME = 'Cookie_Consent';

    const COOKIE_CATEGORY_NAME = 'Cookie_Category_';

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Check if cookie consent has already been saved.
     */
    public function hasCookieConsent(): bool
    {
        return $this->request->cookies->has(self::COOKIE_CONSENT_NAME);
    }

    /**
     * Check if given cookie category is permitted by user.
     */
    public function isCategoryPermitted(string $category): bool
    {
        return $this->request->cookies->get(self::COOKIE_CATEGORY_NAME.$category) === 'true';
    }

    /**
     * Save chosen cookie categories in cookies.
     */
    public function saveCookieConsent(Response $response, array $categories): void
    {
        $this->addCookie($response, self::COOKIE_CONSENT_NAME, date('r'));

        foreach ($categories as $category => $permitted) {
            $this->addCookie($response, self::COOKIE_CATEGORY_NAME.$category, $permitted);
        }
    }

    /**
     * Add cookie to response headers.
     */
    protected function addCookie(Response $response, string $name, string $value): void
    {
        $response->headers->setCookie(
            new Cookie($name, $value, 0, '/', null, null, true, true)
        );
    }
}
