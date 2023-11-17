<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\DependencyInjection;

use ConnectHolland\CookieConsentBundle\Enum\CategoryEnum;
use ConnectHolland\CookieConsentBundle\Enum\PositionEnum;
use ConnectHolland\CookieConsentBundle\Enum\ThemeEnum;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ch_cookie_consent');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->append($this->addCookiesNode())
                ->variableNode('consent_categories')
                    ->defaultValue([CategoryEnum::CATEGORY_TRACKING, CategoryEnum::CATEGORY_MARKETING, CategoryEnum::CATEGORY_SOCIAL_MEDIA])
                    ->info('Set the categories of consent that should be used')
                ->end()
                ->enumNode('theme')
                    ->defaultValue(ThemeEnum::THEME_LIGHT)
                    ->values(ThemeEnum::getAvailableThemes())
                ->end()
                ->enumNode('position')
                    ->defaultValue(PositionEnum::POSITION_TOP)
                    ->values(PositionEnum::getAvailablePositions())
                ->end()
                ->booleanNode('use_logger')
                    ->defaultTrue()
                ->end()
                ->booleanNode('simplified')
                    ->defaultFalse()
                ->end()
                ->scalarNode('form_action')
                    ->defaultNull()
                ->end()
                ->booleanNode('csrf_protection')
                    ->defaultTrue()
                ->end()
                ->arrayNode('legacy')
                    ->arrayPrototype()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('http_only')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addCookiesNode(): ArrayNodeDefinition
    {
        $builder = new TreeBuilder('cookies');
        $node = $builder->getRootNode();

        $node
            ->children()
                ->scalarNode('name_prefix')
                    ->defaultValue('')
                    ->cannotBeEmpty()
                    ->info('Prefix the cookie names, if necessary')
                ->end()
//            TODO: make all array keys optional
                ->append($this->addCookie('consent', 'cookie_name_prefix'))
                ->append($this->addCookie('consent_key', 'cookie_name_prefix'))
                ->append($this->addCookie('consent_categories', 'cookie_name_prefix'))
            ->end();

        return $node;
    }

    private function addCookie(string $name, string $prefix): ArrayNodeDefinition
    {
        $builder = new TreeBuilder($name);
        $node = $builder->getRootNode();

        $node
            ->children()
                ->variableNode('name')
                    ->info('Set the name of the cookie')
                    ->defaultValue($prefix . $name)
                ->end()
                ->booleanNode('http_only')
                    ->info('Set if the cookie should be accessible only through the HTTP protocol')
                    ->defaultValue(true)
                ->end()
                ->booleanNode('secure')
                    ->info('Set if the cookie should only be transmitted over a secure HTTPS connection from the client')
                    ->defaultValue(true)
                ->end()
                ->enumNode('same_site')
                    ->info('Set the value for the SameSite attribute of the cookie')
                    ->values(['lax', 'strict'])
                    ->defaultValue('lax')
                ->end()
                ->scalarNode('expires')
                    ->info('Set the value for the Expires attribute of the cookie')
                    ->defaultValue('P180D')
                ->end()
            ->end();

        return $node;
    }
}
