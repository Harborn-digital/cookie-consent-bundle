<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\Cookie;

use ConnectHolland\CookieConsentBundle\Cookie\CookieChecker;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class CookieCheckerTest extends TestCase
{
    private MockObject $request;
    private CookieChecker $cookieChecker;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->request       = $this->createMock(Request::class);
        $this->cookieChecker = new CookieChecker($this->request);
    }

    /**
     * @dataProvider isCookieConsentSavedByUserDataProvider
     *
     * Test CookieChecker:isCookieConsentSavedByUser
     */
    public function testIsCookieConsentSavedByUser(array $cookies = [], bool $expected = false): void
    {
        $this->request->cookies = new ParameterBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCookieConsentSavedByUser());
    }

    /**
     * Data provider for testIsCookieConsentSavedByUser.
     */
    public static function isCookieConsentSavedByUserDataProvider(): array
    {
        return [
            [['Cookie_Consent' => date('r')], true],
            [['Cookie_Consent' => 'true'], true],
            [['Cookie_Consent' => ''], true],
            [['Cookie Consent' => 'true'], false],
            [['CookieConsent' => 'true'], false],
            [[], false],
        ];
    }

    /**
     * @dataProvider isCategoryAllowedByUserDataProvider
     *
     * Test CookieChecker:isCategoryAllowedByUser
     */
    public function testIsCategoryAllowedByUser(array $cookies = [], string $category = '', bool $expected = false): void
    {
        $this->request->cookies = new ParameterBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCategoryAllowedByUser($category));
    }

    /**
     * Data provider for testIsCategoryAllowedByUser.
     */
    public static function isCategoryAllowedByUserDataProvider(): array
    {
        return [
            [['Cookie_Category_analytics' => 'true'], 'analytics', true],
            [['Cookie_Category_marketing' => 'true'], 'marketing', true],
            [['Cookie_Category_analytics' => 'false'], 'analytics', false],
            [['Cookie Category analytics' => 'true'], 'analytics', false],
            [['Cookie_Category_Analytics' => 'true'], 'analytics', false],
            [['analytics' => 'true'], 'analytics', false],
        ];
    }
}
