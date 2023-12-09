<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CookieConsentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('cookie_consent.cookie_settings', $config['cookie_settings']);
        $container->setParameter('cookie_consent.cookie_settings.name_prefix', $config['cookie_settings']['name_prefix']);
        $container->setParameter('cookie_consent.cookie_settings.cookies', $config['cookie_settings']['cookies']);
        $container->setParameter('cookie_consent.cookie_settings.consent_categories', $config['consent_categories']);
        $container->setParameter('cookie_consent.persist_consent', $config['persist_consent']);
        $container->setParameter('cookie_consent.theme', $config['theme']);
        $container->setParameter('cookie_consent.position', $config['position']);
        $container->setParameter('cookie_consent.form_action', $config['form_action']);
        $container->setParameter('cookie_consent.csrf_protection', $config['csrf_protection']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
