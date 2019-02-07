<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\EventSubscriber;

use ConnectHolland\CookieConsentBundle\EventSubscriber\CookieConsentFormSubscriber;
use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CookieConsentFormSubscriberTest extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactoryInterface;

    /**
     * @var CookieConsentFormSubscriber
     */
    private $cookieConsentFormSubscriber;

    public function setUp()
    {
        $this->formFactoryInterface        = $this->createMock(FormFactoryInterface::class);
        $this->cookieConsentFormSubscriber = new CookieConsentFormSubscriber($this->formFactoryInterface);
    }

    public function testGetSubscribedEvents(): void
    {
        $expectedEvents = [
           KernelEvents::RESPONSE => ['onResponse', 5],
        ];

        $this->assertSame($expectedEvents, $this->cookieConsentFormSubscriber->getSubscribedEvents());
    }

    public function testOnResponse(): void
    {
        $request  = new Request();
        $response = new Response();

        $event = $this->createMock(FilterResponseEvent::class);
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

        $this->cookieConsentFormSubscriber->onResponse($event);
    }
}
