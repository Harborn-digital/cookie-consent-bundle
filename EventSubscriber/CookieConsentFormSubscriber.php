<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\EventSubscriber;

use ConnectHolland\CookieConsentBundle\Cookie\CookieHandler;
use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CookieConsentFormSubscriber implements EventSubscriberInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory   = $formFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
           KernelEvents::RESPONSE => ['onResponse', 5],
        ];
    }

    /**
     * Appends Cookie Consent scripts into body.
     */
    public function onResponse(FilterResponseEvent $event): void
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();

        $form = $this->createCookieConsentForm();
        $form->handleRequest($request);

        // If form is submitted save in cookies, otherwise display cookie consent
        if ($form->isSubmitted() && $form->isValid()) {
            $cookieHandler = new CookieHandler($response);
            $cookieHandler->save($form->getData());
        }
    }

    /**
     * Create cookie consent form.
     */
    protected function createCookieConsentForm(): FormInterface
    {
        return $this->formFactory->create(CookieConsentType::class);
    }
}
