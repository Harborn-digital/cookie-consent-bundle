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
    /**
     * @var Response
     */
    private $response;

    public function setUp(): void
    {
        $this->response = new Response();
    }

    /**
     * Test CookieHandler:save.
     */
    public function testSave(): void
    {
        $this->saveCookieHandler(true);

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
        $this->saveCookieHandler(false);
        $cookies = $this->response->headers->getCookies();
        $this->assertSame(false, $cookies[4]->isHttpOnly());
    }

    /**
     * Test CookieHandler:save with httpOnly true.
     */
    public function testCookieHandlerHttpOnlyIsTrue(): void
    {
        $this->saveCookieHandler(true);
        $cookies = $this->response->headers->getCookies();
        $this->assertSame(true, $cookies[4]->isHttpOnly());
    }

    /**
     * Save CookieHandler.
     */
    public function saveCookieHandler(bool $httpOnly): void
    {
        $cookieHandler = new CookieHandler($httpOnly);

        $cookieHandler->save([
            'analytics'    => 'true',
            'social_media' => 'true',
            'tracking'     => 'false',
        ], 'key-test', $this->response);
    }
}
