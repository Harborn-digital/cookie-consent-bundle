<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\DOM;

use ConnectHolland\CookieConsentBundle\Cookie\CookieHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class DOMParser
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var CookieHandler
     */
    private $cookieHandler;

    /**
     * @var string
     */
    private $cookieConsentTheme;

    public function __construct(EngineInterface $templating, CookieHandler $cookieHandler, string $cookieConsentTheme)
    {
        $this->templating         = $templating;
        $this->cookieHandler      = $cookieHandler;
        $this->cookieConsentTheme = $cookieConsentTheme;
    }

    /**
     * Append content of cookie consent to Kernel Response content.
     */
    public function appendCookieConsent(Response $response, FormInterface $form): string
    {
        $crawler = new HtmlPageCrawler($response->getContent());
        $crawler->filter('body')->append(
            $this->generateCookieConsentContent($form)
        );

        return $crawler->saveHTML();
    }

    /**
     * Generate content of CookieConsent.
     */
    public function generateCookieConsentContent(FormInterface $form): string
    {
        return $this->templating->render('@CHCookieConsent/cookie_consent.html.twig', [
            'form'  => $form->createView(),
            'theme' => $this->cookieConsentTheme,
        ]);
    }
}
