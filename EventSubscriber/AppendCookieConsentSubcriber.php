<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\EventSubscriber;

use ConnectHolland\CookieConsentBundle\Cookie\CookieHandler;
use ConnectHolland\CookieConsentBundle\DOM\DOMParser;
use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AppendCookieConsentSubcriber implements EventSubscriberInterface
{
    /**
     * @var CookieHandler
     */
    private $cookieHandler;

    /**
     * @var DOMParser
     */
    private $domParser;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(CookieHandler $cookieHandler, DOMParser $domParser, FormFactoryInterface $formFactory)
    {
        $this->cookieHandler = $cookieHandler;
        $this->domParser     = $domParser;
        $this->formFactory   = $formFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
           KernelEvents::RESPONSE => ['onResponse'],
        ];
    }

    /**
     * Appends Cookie Consent scripts into body.
     */
    public function onResponse(FilterResponseEvent $event): void
    {
        if ($event->isMasterRequest() === false || $this->cookieHandler->hasCookieConsent()) {
            return;
        }

        $request  = $event->getRequest();
        $response = $event->getResponse();

        $form = $this->createCookieConsentForm();
        $form->handleRequest($request);

        // If form is submitted save in cookies, otherwise display cookie consent
        if ($form->isSubmitted() && $form->isValid()) {
            $this->cookieHandler->saveCookieConsent($response, $form->getData());
        } else {
            $this->showCookieConsent($response, $form);
        }
    }

    /**
     * Create cookie consent form.
     */
    protected function createCookieConsentForm(): FormInterface
    {
        return $this->formFactory->create(CookieConsentType::class);
    }

    /**
     * Append cookie consent to Kernel Response.
     */
    protected function showCookieConsent(Response $response, FormInterface $form)
    {
        $response->setContent(
            $this->domParser->appendCookieConsent($response->getContent(), $form)
        );
    }
}
