<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\Cookie;

use ConnectHolland\CookieConsentBundle\Cookie\CookieChecker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class CookieCheckerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $request;

    /**
     * @var CookieChecker
     */
    private $cookieChecker;

    public function setUp()
    {
        $this->request       = $this->createMock(Request::class);
        $this->cookieChecker = new CookieChecker($this->request);
    }

    /**
     * @dataProvider hasCookiesSavedDataProvider
     *
     * Test CookieChecker:hasCookiesSaved
     */
    public function testHasCookiesSaved(array $cookies = [], bool $expected): void
    {
        $this->request->cookies = new ParameterBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->hasCookiesSaved());
    }

    /**
     * Data provider for testHasCookiesSaved.
     */
    public function hasCookiesSavedDataProvider(): array
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
     * @dataProvider isAllowedDataProvider
     *
     * Test CookieChecker:isAllowed
     */
    public function testIsAllowed(array $cookies = [], string $category, bool $expected): void
    {
        $this->request->cookies = new ParameterBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isAllowed($category));
    }

    /**
     * Data provider for testIsAllowed.
     */
    public function isAllowedDataProvider(): array
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
