<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Controller;

use ConnectHolland\CookieConsentBundle\Cookie\CookieChecker;
use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookieConsentController
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
     * @var CookieChecker
     */
    private $cookieChecker;

    public function __construct(
        \Twig_Environment $twigEnvironment,
        FormFactoryInterface $formFactory,
        CookieChecker $cookieChecker,
        string $cookieConsentTheme
    ) {
        $this->twigEnvironment    = $twigEnvironment;
        $this->formFactory        = $formFactory;
        $this->cookieChecker      = $cookieChecker;
        $this->cookieConsentTheme = $cookieConsentTheme;
    }

    /**
     * Show cookie consent.
     *
     * @Route("/cookie_consent", name="ch_cookie_consent.show")
     */
    public function show(Request $request): Response
    {
        return new Response(
            $this->twigEnvironment->render('@CHCookieConsent/cookie_consent.html.twig', [
                'form'  => $this->createCookieConsentForm()->createView(),
                'theme' => $this->cookieConsentTheme,
            ])
        );

        return $response;
    }

    /**
     * Show cookie consent.
     *
     * @Route("/cookie_consent_alt", name="ch_cookie_consent.show_if_cookie_consent_not_set")
     */
    public function showIfCookieConsentNotSet(Request $request): Response
    {
        if ($this->cookieChecker->isCookieConsentSavedByUser() === false) {
            return $this->show($request);
        }

        return new Response();
    }

    /**
     * Create cookie consent form.
     */
    protected function createCookieConsentForm(): FormInterface
    {
        return $this->formFactory->create(CookieConsentType::class);
    }
}
