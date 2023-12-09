<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Cookie;

use huppys\CookieConsentBundle\Enum\CookieNameEnum;
use Symfony\Component\HttpFoundation\Request;

class CookieChecker
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Check if cookie consent has already been saved.
     */
    public function isCookieConsentSavedByUser(): bool
    {
        return $this->request->cookies->has(CookieNameEnum::COOKIE_CONSENT_NAME);
    }

    /**
     * Check if given cookie category is permitted by user.
     */
    public function isCategoryAllowedByUser(string $category): bool
    {
        return $this->request->cookies->get(CookieNameEnum::getCookieCategoryName($category)) === 'true';
    }
}
