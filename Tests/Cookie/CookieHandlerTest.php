<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\Cookie;

use ConnectHolland\CookieConsentBundle\Cookie\CookieHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CookieHandlerTest extends TestCase
{
    private Response $response;

    public function setUp(): void
    {
        $this->response = new Response();
    }

    /**
     * Test CookieHandler:save.
     */
    public function testSave(): void
    {
        $cookies = [
            'name_prefix' => 'Cookie_',
            'categories' => [
                'analytics',
                'social_media',
                'tracking',
            ],
            'consent' => [
                'name' => 'consent',
                'http_only' => false,
                'secure' => true,
                'same_site' => 'lax',
                'expires' => 'P180D',
            ],
            'consent_key' => [
                'name' => 'consent-key',
                'http_only' => true,
                'secure' => true,
                'same_site' => 'strict',
                'expires' => 'P180D',
            ],
            'consent_categories' => [
                'name' => 'consent-categories',
                'http_only' => true,
                'secure' => true,
                'same_site' => 'lax',
                'expires' => 'P180D',
            ],
        ];
        $this->saveCookieHandler($cookies);

        $cookies = $this->response->headers->getCookies();

        $this->assertCount(5, $cookies);

        $this->assertSame('Cookie_Consent', $cookies[0]->getName());

        $this->assertSame('Cookie_Consent_Key', $cookies[1]->getName());
        $this->assertSame('key-test', $cookies[1]->getValue());

        $this->assertSame('Cookie_Category_analytics', $cookies[2]->getName());
        $this->assertSame('true', $cookies[2]->getValue());

        $this->assertSame('Cookie_Category_social_media', $cookies[3]->getName());
        $this->assertSame('true', $cookies[3]->getValue());

        $this->assertSame('Cookie_Category_tracking', $cookies[4]->getName());
        $this->assertSame('false', $cookies[4]->getValue());
    }

    /**
     * Test CookieHandler:save with httpOnly false.
     */
    public function testCookieHandlerHttpOnlyIsFalse(): void
    {
        $cookies = [
            'name_prefix' => 'Cookie_',
            'categories' => [
                'analytics',
                'social_media',
                'tracking',
            ],
            'consent' => [
                'name' => 'consent',
                'http_only' => false,
                'secure' => true,
                'same_site' => 'lax',
                'expires' => 'P180D',
            ],
            'consent_key' => [
                'name' => 'consent-key',
                'http_only' => true,
                'secure' => true,
                'same_site' => 'strict',
                'expires' => 'P180D',
            ],
            'consent_categories' => [
                'name' => 'consent-categories',
                'http_only' => false,
                'secure' => true,
                'same_site' => 'lax',
                'expires' => 'P180D',
            ],
        ];
        $this->saveCookieHandler($cookies);
        $cookies = $this->response->headers->getCookies();
        $this->assertSame(false, $cookies[4]->isHttpOnly());
    }

    /**
     * Test CookieHandler:save with httpOnly true.
     */
    public function testCookieHandlerHttpOnlyIsTrue(): void
    {
        $cookies = [
            'name_prefix' => 'Cookie_',
            'categories' => [
                'analytics',
                'social_media',
                'tracking',
            ],
            'consent' => [
                'name' => 'consent',
                'http_only' => false,
                'secure' => true,
                'same_site' => 'lax',
                'expires' => 'P180D',
            ],
            'consent_key' => [
                'name' => 'consent-key',
                'http_only' => true,
                'secure' => true,
                'same_site' => 'strict',
                'expires' => 'P180D',
            ],
            'consent_categories' => [
                'name' => 'consent-categories',
                'http_only' => true,
                'secure' => true,
                'same_site' => 'lax',
                'expires' => 'P180D',
            ],
        ];
        $this->saveCookieHandler($cookies);
        $cookies = $this->response->headers->getCookies();
        $this->assertSame(true, $cookies[4]->isHttpOnly());
    }

    /**
     * Save CookieHandler.
     */
    public function saveCookieHandler($cookies): void
    {
        $cookieHandler = new CookieHandler($cookies);

        $cookieHandler->save([
            'analytics' => 'true',
            'social_media' => 'true',
            'tracking' => 'false',
        ], 'key-test', $this->response);
    }
}
