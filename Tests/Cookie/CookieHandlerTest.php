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

    /**
     * @var CookieHandler
     */
    private $cookieHandler;

    public function setUp()
    {
        $this->response       = new Response();
        $this->cookieHandler  = new CookieHandler($this->response);
    }

    /**
     * Test CookieHandler:save.
     */
    public function testSave(): void
    {
        $this->cookieHandler->save([
            'analytics'    => 'true',
            'social_media' => 'true',
            'tracking'     => 'false',
        ]);

        $cookies = $this->response->headers->getCookies();

        $this->assertCount(4, $cookies);

        $this->assertSame('Cookie_Consent', $cookies[0]->getName());

        $this->assertSame('Cookie_Category_analytics', $cookies[1]->getName());
        $this->assertSame('true', $cookies[1]->getValue());

        $this->assertSame('Cookie_Category_social_media', $cookies[2]->getName());
        $this->assertSame('true', $cookies[2]->getValue());

        $this->assertSame('Cookie_Category_tracking', $cookies[3]->getName());
        $this->assertSame('false', $cookies[3]->getValue());
    }
}
