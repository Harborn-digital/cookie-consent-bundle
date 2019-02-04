<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Twig;

use ConnectHolland\CookieConsentBundle\Cookie\CookieHandler;
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
                'chcookieconsent_isCategoryPermitted',
                [$this, 'isCategoryPermitted'],
                ['needs_context' => true]
            ),
        ];
    }

    /**
     * Checks if user has given permission for cookie category.
     */
    public function isCategoryPermitted(array $context, string $category): bool
    {
        $cookieHandler = $this->getCookieHandler($context['app']->getRequest());

        return $cookieHandler->isCategoryPermitted($category);
    }

    /**
     * Get instance of CookieHandler.
     */
    private function getCookieHandler(Request $request): CookieHandler
    {
        return new CookieHandler($request);
    }
}
