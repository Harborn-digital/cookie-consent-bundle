<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\Twig;

use ConnectHolland\CookieConsentBundle\Cookie\CookieChecker;
use ConnectHolland\CookieConsentBundle\Twig\CHCookieConsentTwigExtension;
use PHPUnit\Framework\TestCase;

class CHCookieConsentTwigExtensionTest extends TestCase
{
    /**
     * @var CHCookieConsentTwigExtension
     */
    private $chCookieConsentTwigExtension;

    /**
     * @var MockObject
     */
    private $cookieChecker;

    public function setUp(): void
    {
        $this->cookieChecker                = $this->createMock(CookieChecker::class);
        $this->chCookieConsentTwigExtension = new CHCookieConsentTwigExtension($this->cookieChecker);
    }

    public function testGetFunctions(): void
    {
        $functions = $this->chCookieConsentTwigExtension->getFunctions();

        $this->assertCount(2, $functions);
        $this->assertSame('chcookieconsent_isCookieConsentSavedByUser', $functions[0]->getName());
        $this->assertSame('chcookieconsent_isCategoryAllowedByUser', $functions[1]->getName());
    }

    public function testIsCookieConsentSavedByUser(): void
    {
        $result  = $this->chCookieConsentTwigExtension->isCookieConsentSavedByUser();

        $this->assertSame($result, false);
    }

    public function testIsCategoryAllowedByUser(): void
    {
        $result  = $this->chCookieConsentTwigExtension->isCategoryAllowedByUser('analytics');

        $this->assertSame($result, false);
    }
}
