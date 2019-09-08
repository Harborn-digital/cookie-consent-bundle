<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Cookie;

use ConnectHolland\CookieConsentBundle\Enum\CookieNameEnum;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CookieChecker
 * @package ConnectHolland\CookieConsentBundle\Cookie
 */
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
//
//    /**
//     * @var string
//     */
//    private $cookieKeyName;
//
//    /**
//     * @var string
//     */
//    private $cookieConsentName;

    /**
     * CookieChecker constructor.
     *
     * @param Request $request

     */
    public function __construct(Request $request, CookieNameEnum $cookieNameEnum) //, string $cookiePrefix, string $cookieKeyName, string $cookieConsentName
    {
        $this->request              = $request;
        $this->cookiePrefix         = $cookieNameEnum;

    }

    /**
     * @return bool
     */
    public function isCookieConsentSavedByUser(  ): bool
    {
        return $this->request->cookies->has( CookieNameEnum::$prefixCookie["COOKIE_CONSENT_NAME"]);
    }

    /**
     * Check if given cookie category is permitted by user.
     */
    public function isCategoryAllowedByUser(string $category): bool
    {
        return $this->request->cookies->get(CookieNameEnum::getCookieCategoryName($category)) === 'true';
    }
}
