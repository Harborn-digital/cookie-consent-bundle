<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\EventSubscriber;

use ConnectHolland\CookieConsentBundle\Cookie\CookieLogger;
use ConnectHolland\CookieConsentBundle\EventSubscriber\CookieConsentFormSubscriber;
use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CookieConsentFormSubscriberTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $formFactoryInterface;

    /**
     * @var MockObject
     */
    private $cookieLogger;

    public function setUp(): void
    {
        $this->formFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $this->cookieLogger         = $this->createMock(CookieLogger::class);
    }

    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
           KernelEvents::RESPONSE => ['onResponse'],
        ];

        $cookieConsentFormSubscriber = new CookieConsentFormSubscriber($this->formFactoryInterface, $this->cookieLogger, true);
        $this->assertSame($expectedEvents, $cookieConsentFormSubscriber->getSubscribedEvents());
    }

    public function testOnResponse(): void
    {
        $request  = new Request();
        $response = new Response();

        $event = $this->createMock(ResponseEvent::class);
        $event
            ->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);
        $event
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($response);

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

        $cookieConsentFormSubscriber = new CookieConsentFormSubscriber($this->formFactoryInterface, $this->cookieLogger, true);
        $cookieConsentFormSubscriber->onResponse($event);
    }

    public function testOnResponseWithLoggerDisabled(): void
    {
        $request  = new Request();
        $response = new Response();

        $event = $this->createMock(ResponseEvent::class);
        $event
            ->expects($this->once())
            ->method('getRequest')
            ->willReturn($request);
        $event
            ->expects($this->once())
            ->method('getResponse')
            ->willReturn($response);

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

        $cookieConsentFormSubscriber = new CookieConsentFormSubscriber($this->formFactoryInterface, $this->cookieLogger, false);
        $cookieConsentFormSubscriber->onResponse($event);
    }
}
