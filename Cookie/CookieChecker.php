<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Cookie;

use ConnectHolland\CookieConsentBundle\Enum\CookieNameEnum;
use Symfony\Component\HttpFoundation\RequestStack;

class CookieChecker
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Check if cookie consent has already been saved.
     */
    public function isCookieConsentSavedByUser(): bool
    {
        return $this->requestStack->getCurrentRequest()->cookies->has(CookieNameEnum::COOKIE_CONSENT_NAME);
    }

    /**
     * Check if given cookie category is permitted by user.
     */
    public function isCategoryAllowedByUser(string $category): bool
    {
        return $this->requestStack->getCurrentRequest()->cookies->get(CookieNameEnum::getCookieCategoryName($category)) === 'true';
    }
}
