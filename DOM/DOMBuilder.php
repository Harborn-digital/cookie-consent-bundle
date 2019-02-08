<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\DOM;

use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class DOMBuilder
{
    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var string
     */
    private $cookieConsentTheme;

    public function __construct(\Twig_Environment $twigEnvironment, FormFactoryInterface $formFactory, string $cookieConsentTheme)
    {
        $this->twigEnvironment    = $twigEnvironment;
        $this->formFactory        = $formFactory;
        $this->cookieConsentTheme = $cookieConsentTheme;
    }

    /**
     * Generate content of CookieConsent.
     */
    public function buildCookieConsentDom(): string
    {
        return $this->twigEnvironment->render('@CHCookieConsent/cookie_consent.html.twig', [
            'form'  => $this->createCookieConsentForm()->createView(),
            'theme' => $this->cookieConsentTheme,
        ]);
    }

    /**
     * Create cookie consent form.
     */
    protected function createCookieConsentForm(): FormInterface
    {
        return $this->formFactory->create(CookieConsentType::class);
    }
}
