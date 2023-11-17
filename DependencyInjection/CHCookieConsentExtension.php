<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CHCookieConsentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('ch_cookie_consent.consent_categories', $config['consent_categories']);
        $container->setParameter('ch_cookie_consent.cookies', $config['cookies']);
        $container->setParameter('ch_cookie_consent.cookies.name_prefix', $config['cookies']['name_prefix']);
        $container->setParameter('ch_cookie_consent.cookies.consent', $config['cookies']['consent']);
        $container->setParameter('ch_cookie_consent.cookies.consent_key', $config['cookies']['consent_key']);
        $container->setParameter('ch_cookie_consent.cookies.consent_categories', $config['cookies']['consent_categories']);
//        better name 'persist consent' instead of 'use_logger'
        $container->setParameter('ch_cookie_consent.use_logger', $config['use_logger']);
        $container->setParameter('ch_cookie_consent.theme', $config['theme']);
        $container->setParameter('ch_cookie_consent.position', $config['position']);
        $container->setParameter('ch_cookie_consent.simplified', $config['simplified']);
        $container->setParameter('ch_cookie_consent.form_action', $config['form_action']);
        $container->setParameter('ch_cookie_consent.csrf_protection', $config['csrf_protection']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }
}
