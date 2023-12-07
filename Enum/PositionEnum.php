<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Enum;

class PositionEnum
{
    const POSITION_TOP     = 'top';
    const POSITION_BOTTOM  = 'bottom';
    const POSITION_DIALOG  = 'dialog';

    /**
     * @var array
     */
    private static array $positions = [
        self::POSITION_TOP,
        self::POSITION_BOTTOM,
        self::POSITION_DIALOG,
    ];

    /**
     * Get all cookie consent positions.
     */
    public static function getAvailablePositions(): array
    {
        return self::$positions;
    }
}
