<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\Controller;

use huppys\CookieConsentBundle\Cookie\CookieChecker;
use huppys\CookieConsentBundle\Form\CookieConsentType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsController]
class CookieConsentController
{
    private Environment $twigEnvironment;
    private FormFactoryInterface $formFactory;
    private CookieChecker $cookieChecker;
    private RouterInterface $router;
    private string $cookieConsentTheme;
    private string $cookieConsentPosition;
    private LocaleAwareInterface $translator;
    private string|null $formAction;

    public function __construct(
        Environment $twigEnvironment,
        FormFactoryInterface $formFactory,
        CookieChecker $cookieChecker,
        RouterInterface $router,
        string $cookieConsentTheme,
        string $cookieConsentPosition,
        LocaleAwareInterface $translator,
        string $formAction = null
    ) {
        $this->twigEnvironment         = $twigEnvironment;
        $this->formFactory             = $formFactory;
        $this->cookieChecker           = $cookieChecker;
        $this->router                  = $router;
        $this->cookieConsentTheme      = $cookieConsentTheme;
        $this->cookieConsentPosition   = $cookieConsentPosition;
        $this->translator              = $translator;
        $this->formAction              = $formAction;
    }

    /**
     * Show cookie consent.
     */
    #[Route('/cookie_consent', name: 'cookie_consent.show')]
    public function show(Request $request): Response
    {
        $this->setLocale($request);

        try {
            $response = new Response(
                $this->twigEnvironment->render('@CookieConsent/cookie_consent.html.twig', [
                    'form' => $this->createCookieConsentForm()->createView(),
                    'theme' => $this->cookieConsentTheme,
                    'position' => $this->cookieConsentPosition,
                ])
            );

            // Cache in ESI should not be shared
            $response->setPrivate();
            $response->setMaxAge(0);

            return $response;
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show cookie consent.
     */
    #[Route('/cookie_consent_alt', name: 'cookie_consent.show_if_cookie_consent_not_set')]
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
        if ($this->formAction === null) {
            $form = $this->formFactory->create(CookieConsentType::class);
        } else {
            $form = $this->formFactory->create(
                CookieConsentType::class,
                null,
                [
                    'action' => $this->router->generate($this->formAction),
                ]
            );
        }

        return $form;
    }

    /**
     * Set locale if available as GET parameter.
     */
    protected function setLocale(Request $request): void
    {
        $locale = $request->get('locale');
        if (empty($locale) === false) {
            $this->translator->setLocale($locale);
            $request->setLocale($locale);
        }
    }
}
