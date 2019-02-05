<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Twig;

use ConnectHolland\CookieConsentBundle\Cookie\CookieChecker;
use Symfony\Component\HttpFoundation\Request;
use Twig_Extension;
use Twig_SimpleFunction;

class CHCookieConsentTwigExtension extends Twig_Extension
{
    /**
     * Register all custom twig functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'chcookieconsent_isSaved',
                [$this, 'isSaved'],
                ['needs_context' => true]
            ),
            new Twig_SimpleFunction(
                'chcookieconsent_isAllowed',
                [$this, 'isAllowed'],
                ['needs_context' => true]
            ),
        ];
    }

    /**
     * Checks if user has sent cookie consent form.
     */
    public function isSaved(array $context): bool
    {
        $cookieChecker = $this->getCookieChecker($context['app']->getRequest());

        return $cookieChecker->hasCookiesSaved();
    }

    /**
     * Checks if user has given permission for cookie category.
     */
    public function isAllowed(array $context, string $category): bool
    {
        $cookieChecker = $this->getCookieChecker($context['app']->getRequest());

        return $cookieChecker->isAllowed($category);
    }

    /**
     * Get instance of CookieChecker.
     */
    private function getCookieChecker(Request $request): CookieChecker
    {
        return new CookieChecker($request);
    }
}
