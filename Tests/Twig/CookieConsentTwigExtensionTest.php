<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\Tests\Twig;

use huppys\CookieConsentBundle\Twig\CookieConsentTwigExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\HttpFoundation\Request;

class CookieConsentTwigExtensionTest extends TestCase
{
    private CookieConsentTwigExtension $CookieConsentTwigExtension;

    public function setUp(): void
    {
        $this->CookieConsentTwigExtension = new CookieConsentTwigExtension('light');
    }

    public function testGetFunctions(): void
    {
        $functions = $this->CookieConsentTwigExtension->getFunctions();

        $this->assertCount(2, $functions);
        $this->assertSame('cookieconsent_isCookieConsentSavedByUser', $functions[0]->getName());
        $this->assertSame('cookieconsent_isCategoryAllowedByUser', $functions[1]->getName());
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
        $result  = $this->CookieConsentTwigExtension->isCookieConsentSavedByUser($context);

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
        $result  = $this->CookieConsentTwigExtension->isCategoryAllowedByUser($context, 'analytics');

        $this->assertSame($result, false);
    }
}
