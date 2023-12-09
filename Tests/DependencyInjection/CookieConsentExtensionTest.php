<?php

declare(strict_types=1);



namespace huppys\CookieConsentBundle\Tests\DependencyInjection;

use huppys\CookieConsentBundle\DependencyInjection\CookieConsentExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class CookieConsentExtensionTest extends TestCase
{
    private CookieConsentExtension $CookieConsentExtension;

    private ContainerBuilder $configuration;

    public function setUp(): void
    {
        $this->CookieConsentExtension = new CookieConsentExtension();
        $this->configuration = new ContainerBuilder();
    }

    public function testFullConfiguration(): void
    {
        $this->createConfiguration($this->getFullConfig());

        $this->assertParameter(['tracking', 'marketing', 'social_media'], 'cookie_consent.cookie_settings.consent_categories');
        $this->assertParameter('dark', 'cookie_consent.theme');
        $this->assertParameter('top', 'cookie_consent.position');
    }

    public function testInvalidConfiguration(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->createConfiguration($this->getInvalidConfig());
    }

    public function testCookieNamesContainPrefix(): void
    {
        $this->createConfiguration($this->getFullConfig());
        $this->assertParameter('test_', 'cookie_consent.cookie_settings.name_prefix');
    }

    public function testCookieSettingsIsAnArray(): void
    {
        $this->createConfiguration($this->getFullConfig());
        $this->assertIsArray($this->configuration->getParameter("cookie_consent.cookie_settings"));
    }

    /**
     * create configuration.
     */
    protected function createConfiguration(array $config): void
    {
        $this->CookieConsentExtension->load([$config], $this->configuration);

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
cookie_settings:
    name_prefix: 'test_'
    cookies:
        consent_cookie:
            name: 'consent'
            http_only: false
            secure: true
            same_site: 'strict'
            expires: 'P180D'
        consent_key_cookie:
            name: 'consent_key'
            http_only: true
            secure: true
            same_site: 'strict'
            expires: 'P180D'
        consent_categories_cookie:
            name: 'consent_categories'
            http_only: true
            secure: true
            same_site: 'lax'
            expires: 'P180D'
theme: 'dark'
position: 'top'
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
