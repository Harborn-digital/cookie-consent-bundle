<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\Twig;

use ConnectHolland\CookieConsentBundle\Twig\CHCookieConsentTwigExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\HttpFoundation\Request;

class CHCookieConsentTwigExtensionTest extends TestCase
{
    private CHCookieConsentTwigExtension $chCookieConsentTwigExtension;

    public function setUp(): void
    {
        $this->chCookieConsentTwigExtension = new CHCookieConsentTwigExtension();
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
        $request  = new Request();

        $appVariable = $this->createMock(AppVariable::class);
        $appVariable
            ->expects($this->once())
            ->method('getRequest')
            ->wilLReturn($request);

        $context = ['app' => $appVariable];
        $result  = $this->chCookieConsentTwigExtension->isCookieConsentSavedByUser($context);

        $this->assertSame($result, false);
    }

    public function testIsCategoryAllowedByUser(): void
    {
        $request  = new Request();

        $appVariable = $this->createMock(AppVariable::class);
        $appVariable
            ->expects($this->once())
            ->method('getRequest')
            ->wilLReturn($request);

        $context = ['app' => $appVariable];
        $result  = $this->chCookieConsentTwigExtension->isCategoryAllowedByUser($context, 'analytics');

        $this->assertSame($result, false);
    }
}
