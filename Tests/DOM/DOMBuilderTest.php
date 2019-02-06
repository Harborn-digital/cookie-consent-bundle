<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\DOM;

use ConnectHolland\CookieConsentBundle\DOM\DOMBuilder;
use ConnectHolland\CookieConsentBundle\Form\CookieConsentType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Templating\EngineInterface;

class DOMBuilderTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $templating;

    /**
     * @var MockObject
     */
    private $formFactory;

    /**
     * @var DOMBuilder
     */
    private $domBuilder;

    public function setUp()
    {
        $this->templating  = $this->createMock(EngineInterface::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->domBuilder  = new DOMBuilder($this->templating, $this->formFactory, 'dark');
    }

    public function testBuildConsentDom(): void
    {
        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->with(CookieConsentType::class)
            ->willReturn($this->createMock(FormInterface::class));

        $this->templating
            ->expects($this->once())
            ->method('render')
            ->willReturn('test');

        $this->domBuilder->buildCookieConsentDom();
    }
}
