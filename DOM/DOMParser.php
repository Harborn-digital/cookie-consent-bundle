<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\DOM;

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
     * @var string
     */
    private $cookieConsentTheme;

    public function __construct(EngineInterface $templating, string $cookieConsentTheme)
    {
        $this->templating         = $templating;
        $this->cookieConsentTheme = $cookieConsentTheme;
    }

    /**
     * Append content of cookie consent to Kernel Response content.
     */
    public function appendCookieConsent(string $content, FormInterface $form): string
    {
        $crawler = new HtmlPageCrawler($content);
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
