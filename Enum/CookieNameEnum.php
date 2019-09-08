<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Enum;

class CookieNameEnum
{
    const COOKIE_PREFIX                 = 'cookie_';
    const COOKIE_CONSENT_NAME           = 'Consent';
    const COOKIE_CONSENT_KEY_NAME       = 'Key';
    const COOKIE_CATEGORY_NAME_PREFIX   = 'Category_';


    /**
     * @var array
     */
    public static $prefixCookie = [
        "COOKIE_CONSENT_NAME"           => "",
        "COOKIE_CONSENT_KEY_NAME"       => "",
        "COOKIE_CATEGORY_NAME_PREFIX"   => "",
    ];

    private static $Prefix;
    private static $Consent_name;
    private static $Consent_key;
    private static $Consent_category;

    public function __construct(string $cookiePrefix, string $cookieKeyName, string $cookieConsentName, string $cookieConsentCategory) //, string $cookiePrefix
    {
        self::$Prefix               = $cookiePrefix;
        self::$Consent_name         = $cookieConsentName;
        self::$Consent_key          = $cookieKeyName;
        self::$Consent_category     = $cookieConsentCategory;

        self::$prefixCookie = [
            "COOKIE_CONSENT_NAME"           => self::$Prefix . self::$Consent_name ,
            "COOKIE_CONSENT_KEY_NAME"       => self::$Prefix . self::$Consent_name . self::$Consent_key ,
            "COOKIE_CATEGORY_NAME_PREFIX"   => self::$Prefix . self::$Consent_category ,
            ];
    }
    /**
     * Get all cookie consent prefix (this was only for test).
     */
    public static function getAvailableCookie ( ){

        self::$prefixCookie = [
            "COOKIE_CONSENT_NAME"           => self::$Prefix . self::$Consent_name ,
            "COOKIE_CONSENT_KEY_NAME"       => self::$Prefix . self::$Consent_key ,
            "COOKIE_CATEGORY_NAME_PREFIX"   => self::$Prefix . self::$Consent_category ,
        ];

        return self::$prefixCookie;
    }

    /**
     * Get cookie category name.
     *
     * @param string $category
     *
     * @return string
     */
    public static function getCookieCategoryName(string $category): string
    {
        return self::$Prefix . self::$Consent_category.$category;
    }
}
