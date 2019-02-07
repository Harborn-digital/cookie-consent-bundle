<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\Controller;

use ConnectHolland\CookieConsentBundle\Controller\CookieConsentController;
use ConnectHolland\CookieConsentBundle\DOM\DOMBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class CookieConsentControllerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $domBuilder;

    /**
     * @var CookieConsentController
     */
    private $cookieConsentController;

    public function setUp()
    {
        $this->domBuilder              = $this->createMock(DOMBuilder::class);
        $this->cookieConsentController = new CookieConsentController($this->domBuilder);
    }

    public function testShowPost()
    {
        $this->domBuilder
            ->expects($this->once())
            ->method('buildCookieConsentDom')
            ->willReturn('');

        $response = $this->cookieConsentController->showCookieConsent();

        $this->assertInstanceOf(Response::class, $response);
    }
}
