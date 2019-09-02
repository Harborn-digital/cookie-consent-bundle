<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Cookie;

use ConnectHolland\CookieConsentBundle\Enum\CookieNameEnum;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class CookieChecker
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var string
     */
    private $cookiePrefix;

    /**
     * @var string
     */
    private $cookieKeyName;

    /**
     * @var string
     */
    private $cookieConsentName;

    /**
     * CookieChecker constructor.
     *
     * @param Request $request

     */
    public function __construct(Request $request, string $cookiePrefix, string $cookieKeyName, string $cookieConsentName)
    {
        $this->request              = $request;
        $this->cookiePrefix         = $cookiePrefix;
        $this->cookieKeyName        = $cookieKeyName;
        $this->cookieConsentName    = $cookieConsentName;
    }

    /**
     * Check if cookie consent has already been saved.
     */
//    public function isCookieConsentSavedByUser(  ): bool
//    {
//        return $this->request->cookies->has(CookieNameEnum::COOKIE_CONSENT_NAME);
//    }
    /**
     * @return bool
     */
    public function isCookieConsentSavedByUser(  ): bool
    {
        return $this->request->cookies->has($this->cookieConsentName);
    }

    /**
     * Check if given cookie category is permitted by user.
     */
    public function isCategoryAllowedByUser(string $category): bool
    {
        return $this->request->cookies->get(CookieNameEnum::getCookieCategoryName($category, $this->cookieConsentName)) === 'true';
    }
}
