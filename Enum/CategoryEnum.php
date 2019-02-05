<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Enum;

class CategoryEnum
{
    const CATEGORY_ANALYTICS    = 'analytics';
    const CATEGORY_TRACKING     = 'tracking';
    const CATEGORY_MARKETING    = 'marketing';
    const CATEGORY_SOCIAL_MEDIA = 'social_media';

    /**
     * @var array
     */
    private static $categories = [
        self::CATEGORY_ANALYTICS,
        self::CATEGORY_TRACKING,
        self::CATEGORY_MARKETING,
        self::CATEGORY_SOCIAL_MEDIA,
    ];

    /**
     * Get all cookie consent categories.
     */
    public static function getAvailableCategories(): array
    {
        return self::$categories;
    }
}
