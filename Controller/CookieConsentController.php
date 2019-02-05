<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Controller;

use ConnectHolland\CookieConsentBundle\DOM\DOMBuilder;
use Symfony\Component\HttpFoundation\Response;

class CookieConsentController
{
    /**
     * @var DOMBuilder
     */
    private $domBuilder;

    public function __construct(DOMBuilder $domBuilder)
    {
        $this->domBuilder = $domBuilder;
    }

    /**
     * Show cookie consent.
     */
    public function showCookieConsent(): Response
    {
        return new Response(
            $this->domBuilder->buildCookieConsentDom()
        );
    }
}
