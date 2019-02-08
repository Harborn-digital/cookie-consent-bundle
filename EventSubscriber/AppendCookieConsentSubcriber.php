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
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @var array
     */
    private $excludedRoutes;

    /**
     * @var array
     */
    private $excludedPaths;

    public function __construct(CookieChecker $cookieChecker, DOMBuilder $domBuilder, DOMParser $domParser, array $excludedRoutes, array $excludedPaths)
    {
        $this->cookieChecker  = $cookieChecker;
        $this->domBuilder     = $domBuilder;
        $this->domParser      = $domParser;
        $this->excludedRoutes = $excludedRoutes;
        $this->excludedPaths  = $excludedPaths;
    }

    public static function getSubscribedEvents(): array
    {
        return [
           KernelEvents::RESPONSE => ['onResponse', 0],
        ];
    }

    /**
     * Appends cookie consent scripts into body.
     */
    public function onResponse(FilterResponseEvent $event): void
    {
        if ($this->shouldAppendCookieConsent($event)) {
            $this->appendCookieConsent($event->getResponse());
        }
    }

    /**
     * Check if cookie consent should be appended to page.
     */
    protected function shouldAppendCookieConsent(FilterResponseEvent $event): bool
    {
        return
            $event->isMasterRequest() &&
            $event->getResponse()->getStatusCode() === 200 &&
            $this->cookieChecker->isCookieConsentSavedByUser() === false &&
            $this->isExcludedRequest($event->getRequest()) === false;
    }

    /**
     * Append cookie consent to Kernel Response.
     */
    protected function appendCookieConsent(Response $response): void
    {
        $response->setContent(
            $this->domParser->appendToBody(
                $response->getContent(),
                $this->domBuilder->buildCookieConsentDom()
            )
        );
    }

    /**
     * Check if route or path is within the excluded routes or paths.
     */
    protected function isExcludedRequest(Request $request): bool
    {
        if (in_array($request->get('_route'), $this->excludedRoutes)) {
            return true;
        }

        if (in_array($request->getRequestUri(), $this->excludedPaths)) {
            return true;
        }

        return false;
    }
}
