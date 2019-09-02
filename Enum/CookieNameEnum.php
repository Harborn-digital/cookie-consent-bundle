<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Enum;

class CookieNameEnum
{
    const COOKIE_CONSENT_NAME = 'cookie_Consent';

    const COOKIE_CONSENT_KEY_NAME = 'cookie_Consent_Key';

    const COOKIE_CATEGORY_NAME_PREFIX = 'cookie_Category_';

    /**
     * CookieNameEnum constructor.
     */
    public function __construct(string $cookiePrefix, string $cookieKeyName, string $cookieConsentName)
    {
        $this->cookiePrefix = $cookieKeyName;
    }


    /**
     * Get cookie category name.
     */
    public static function getCookieCategoryName(string $category, string $cookieConsentName): string
    {
        return $cookieConsentName.$category;
    }
}
