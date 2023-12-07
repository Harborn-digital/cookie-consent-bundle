<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\DependencyInjection;

use huppys\CookieConsentBundle\Enum\CategoryEnum;
use huppys\CookieConsentBundle\Enum\CookieNameEnum;
use huppys\CookieConsentBundle\Enum\PositionEnum;
use huppys\CookieConsentBundle\Enum\ThemeEnum;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cookie_consent');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->append($this->addCookieSettingsNode())
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
                ->scalarNode('form_action')
                    ->defaultNull()
                ->end()
                ->booleanNode('csrf_protection')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addCookieSettingsNode(): ArrayNodeDefinition
    {
        $builder = new TreeBuilder('cookie_settings');
        $node = $builder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('name_prefix')
                    ->defaultValue('')
                    ->info('Prefix the cookie names, if necessary')
                ->end()
                ->variableNode('consent_categories')
                    ->defaultValue([CategoryEnum::CATEGORY_TRACKING, CategoryEnum::CATEGORY_MARKETING, CategoryEnum::CATEGORY_SOCIAL_MEDIA])
                    ->info('Set the categories of consent that should be used')
                ->end()
                ->arrayNode('cookies')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->addCookie('consent_cookie', CookieNameEnum::COOKIE_CONSENT_NAME))
                        ->append($this->addCookie('consent_key_cookie', CookieNameEnum::COOKIE_CONSENT_KEY_NAME))
                        ->append($this->addCookie('consent_categories_cookie', CookieNameEnum::COOKIE_CATEGORY_NAME_PREFIX))
                    ->end()
                ->end()
            ->end();
        return $node;
    }

    private function addCookie(string $key, string $name): ArrayNodeDefinition
    {
        $builder = new TreeBuilder($key);
        $node = $builder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->canBeDisabled()
            ->children()
                ->variableNode('name')
                    ->info('Set the name of the cookie')
                    ->defaultValue($name)
                ->end()
                ->booleanNode('http_only')
                    ->info('Set if the cookie should be accessible only through the HTTP protocol')
                    ->defaultTrue()
                ->end()
                ->booleanNode('secure')
                    ->info('Set if the cookie should only be transmitted over a secure HTTPS connection from the client')
                    ->defaultTrue()
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
