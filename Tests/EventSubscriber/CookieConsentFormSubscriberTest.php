<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\EventSubscriber;

use ConnectHolland\CookieConsentBundle\Cookie\CookieHandler;
use ConnectHolland\CookieConsentBundle\Cookie\CookieLogger;
use ConnectHolland\CookieConsentBundle\EventSubscriber\CookieConsentFormSubscriber;
use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class CookieConsentFormSubscriberTest extends TestCase
{
    private MockObject $formFactoryInterface;

    private MockObject $cookieLogger;

    private MockObject $cookieHandler;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->formFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $this->cookieLogger = $this->createMock(CookieLogger::class);
        $this->cookieHandler = $this->createMock(CookieHandler::class);
    }

    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
            ResponseEvent::class => ['onResponse'],
        ];

        $cookieConsentFormSubscriber = new CookieConsentFormSubscriber($this->formFactoryInterface, $this->cookieLogger, $this->cookieHandler, true);
        $this->assertSame($expectedEvents, $cookieConsentFormSubscriber->getSubscribedEvents());
    }

    /**
     * @throws Exception
     */
    public function testOnResponse(): void
    {
        $request = new Request();
        $response = new Response();
        $event = $this->getResponseEvent($request, $response);

        $form = $this->createMock(FormInterface::class);
        $form
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);
        $form
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);
        $form
            ->expects($this->once())
            ->method('getData')
            ->willReturn([]);

        $this->formFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->with(CookieConsentType::class)
            ->willReturn($form);

        $this->cookieLogger
            ->expects($this->once())
            ->method('log');

        $cookieConsentFormSubscriber = new CookieConsentFormSubscriber($this->formFactoryInterface, $this->cookieLogger, $this->cookieHandler, true);
        $cookieConsentFormSubscriber->onResponse($event);
    }

    /**
     * @throws Exception
     */
    public function testOnResponseWithLoggerDisabled(): void
    {
        $request = new Request();
        $response = new Response();
        $event = $this->getResponseEvent($request, $response);

        $form = $this->createMock(FormInterface::class);
        $form
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);
        $form
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);
        $form
            ->expects($this->once())
            ->method('getData')
            ->willReturn([]);

        $this->formFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->with(CookieConsentType::class)
            ->willReturn($form);

        $this->cookieLogger
            ->expects($this->never())
            ->method('log');

        $cookieConsentFormSubscriber = new CookieConsentFormSubscriber($this->formFactoryInterface, $this->cookieLogger, $this->cookieHandler, false);
        $cookieConsentFormSubscriber->onResponse($event);
    }

    /**
     * @throws Exception
     */
    private function getResponseEvent(Request $request, Response $response): ResponseEvent
    {
        $kernel = $this->createMock(HttpKernelInterface::class);
        return new ResponseEvent($kernel, $request, 200, $response);
    }
}
