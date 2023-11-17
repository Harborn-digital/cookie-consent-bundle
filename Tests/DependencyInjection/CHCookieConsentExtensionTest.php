<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\DependencyInjection;

use ConnectHolland\CookieConsentBundle\DependencyInjection\CHCookieConsentExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class CHCookieConsentExtensionTest extends TestCase
{
    private CHCookieConsentExtension $chCookieConsentExtension;

    private ContainerBuilder $configuration;

    public function setUp(): void
    {
        $this->chCookieConsentExtension = new CHCookieConsentExtension();
        $this->configuration            = new ContainerBuilder();
    }

    public function testFullConfiguration(): void
    {
        $this->createConfiguration($this->getFullConfig());

        $this->assertParameter(['tracking', 'marketing', 'social_media'], 'ch_cookie_consent.consent_categories');
        $this->assertParameter('dark', 'ch_cookie_consent.theme');
        $this->assertParameter('top', 'ch_cookie_consent.position');
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->createConfiguration($this->getInvalidConfig());
    }

    public function testCookieNamesContainPrefix(): void
    {
        $this->createConfiguration($this->getFullConfig());
        $this->assertParameter('test_', 'ch_cookie_consent.cookies.consent_key');
    }

    /**
     * create configuration.
     */
    protected function createConfiguration(array $config): void
    {
        $this->chCookieConsentExtension->load([$config], $this->configuration);

        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
     * get full config.
     */
    protected function getFullConfig(): array
    {
        $yaml = <<<EOF
consent_categories:
- 'tracking'
- 'marketing'
- 'social_media'
cookies:
  name_prefix: 'test_'
  consent:
    name: 'cookie-consent'
    http_only: false
  consent_key:
    name: 'cookie-consent-key'
  consent_categories:
    name: 'cookie-category'
    http_only: false
theme: 'dark'
position: 'top'
simplified: false
csrf_protection: true
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * get invalid config.
     */
    protected function getInvalidConfig(): array
    {
        $yaml = <<<EOF
theme: 'not_existing'
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * Test if parameter is set.
     */
    private function assertParameter($value, $key): void
    {
        $this->assertSame($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }
}
