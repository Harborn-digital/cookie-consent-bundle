<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\Tests\Cookie;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\InputBag;
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
        $this->request->cookies = new InputBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCookieConsentSavedByUser());
    }

    /**
     * Data provider for testIsCookieConsentSavedByUser.
     */
    public static function isCookieConsentSavedByUserDataProvider(): array
    {
        return [
            [['consent' => date('r')], true],
            [['consent' => 'true'], true],
            [['consent' => ''], true],
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
        $this->request->cookies = new InputBag($cookies);

        $this->assertSame($expected, $this->cookieChecker->isCategoryAllowedByUser($category));
    }

    /**
     * Data provider for testIsCategoryAllowedByUser.
     */
    public static function isCategoryAllowedByUserDataProvider(): array
    {
        return [
            [['consent-category-analytics' => 'true'], 'analytics', true],
            [['consent-category-marketing' => 'true'], 'marketing', true],
            [['Cookie_Category_analytics' => 'false'], 'analytics', false],
            [['Cookie Category analytics' => 'true'], 'analytics', false],
            [['Cookie_Category_Analytics' => 'true'], 'analytics', false],
            [['analytics' => 'true'], 'analytics', false],
        ];
    }
}
