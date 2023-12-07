<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Enum;

class ThemeEnum
{
    const THEME_LIGHT = 'light';
    const THEME_DARK  = 'dark';


    private static array $themes = [
        self::THEME_LIGHT,
        self::THEME_DARK,
    ];

    /**
     * Get all cookie consent themes.
     */
    public static function getAvailableThemes(): array
    {
        return self::$themes;
    }
}
