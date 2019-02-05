<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\DependencyInjection;

use ConnectHolland\CookieConsentBundle\Enum\CategoryEnum;
use ConnectHolland\CookieConsentBundle\Enum\ThemeEnum;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('ch_cookie_consent');

        $rootNode
            ->children()
                ->variableNode('categories')
                    ->defaultValue([CategoryEnum::CATEGORY_TRACKING, CategoryEnum::CATEGORY_MARKETING, CategoryEnum::CATEGORY_SOCIAL_MEDIA])
                    ->validate()
                    ->always(function ($values) {
                        foreach ((array) $values as $value) {
                            if (!in_array($value, CategoryEnum::getAvailableCategories())) {
                                throw new InvalidTypeException(sprintf('Invalid cookie type %s', $value));
                            }
                        }

                        return (array) $values;
                    })
                    ->end()
                ->end()
                ->scalarNode('theme')
                    ->defaultValue(ThemeEnum::THEME_LIGHT)
                    ->validate()
                        ->ifNotInArray(ThemeEnum::getAvailableThemes())
                        ->thenInvalid('Invalid theme %s')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
