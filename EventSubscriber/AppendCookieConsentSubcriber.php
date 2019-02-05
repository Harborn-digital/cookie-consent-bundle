<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\EventSubscriber;

use ConnectHolland\CookieConsentBundle\Cookie\CookieChecker;
use ConnectHolland\CookieConsentBundle\DOM\DOMBuilder;
use ConnectHolland\CookieConsentBundle\DOM\DOMParser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AppendCookieConsentSubcriber implements EventSubscriberInterface
{
    /**
     * @var CookieChecker
     */
    private $cookieChecker;

    /**
     * @var DOMBuilder
     */
    private $domBuilder;

    /**
     * @var DOMParser
     */
    private $domParser;

    public function __construct(CookieChecker $cookieChecker, DOMBuilder $domBuilder, DOMParser $domParser)
    {
        $this->cookieChecker = $cookieChecker;
        $this->domBuilder    = $domBuilder;
        $this->domParser     = $domParser;
    }

    public static function getSubscribedEvents(): array
    {
        return [
           KernelEvents::RESPONSE => ['onResponse', 0],
        ];
    }

    /**
     * Appends Cookie Consent scripts into body.
     */
    public function onResponse(FilterResponseEvent $event): void
    {
        if ($event->isMasterRequest() === false || $this->cookieChecker->hasCookiesSaved()) {
            return;
        }

        $this->appendCookieConsent($event->getResponse());
    }

    /**
     * Append cookie consent to Kernel Response.
     */
    protected function appendCookieConsent(Response $response)
    {
        $response->setContent(
            $this->domParser->appendToBody(
                $response->getContent(),
                $this->domBuilder->buildCookieConsentDom()
            )
        );
    }
}
