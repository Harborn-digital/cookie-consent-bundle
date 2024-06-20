<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\Cookie;

use ConnectHolland\CookieConsentBundle\Cookie\CookieLogger;
use ConnectHolland\CookieConsentBundle\Entity\CookieConsentLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CookieLoggerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $registry;

    /**
     * @var MockObject
     */
    private $requestStack;

    /**
     * @var MockObject
     */
    private $request;

    /**
     * @var MockObject
     */
    private $entityManager;

    /**
     * @var CookieLogger
     */
    private $cookieLogger;

    public function setUp(): void
    {
        $this->registry           = $this->createMock(ManagerRegistry::class);
        $this->requestStack       = $this->createMock(RequestStack::class);
        $this->request            = $this->createMock(Request::class);
        $this->entityManager      = $this->createMock(EntityManagerInterface::class);

        $this->requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->willReturn($this->request);

        $this->registry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with(CookieConsentLog::class)
            ->willReturn($this->entityManager);

        $this->cookieLogger = new CookieLogger($this->registry, $this->requestStack);
    }

    /**
     * Test CookieLogger:log.
     */
    public function testLog(): void
    {
        $this->request
            ->expects($this->once())
            ->method('getClientIp')
            ->willReturn('127.0.0.1');

        $this->entityManager
            ->expects($this->exactly(3))
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->with();

        $this->cookieLogger->log([
            'analytics'    => 'true',
            'social_media' => 'true',
            'tracking'     => 'false',
        ], 'key-test');
    }

    /**
     * Test CookieLogger:log.
     */
    public function testLogWithNullIp(): void
    {
        $this->request
            ->expects($this->once())
            ->method('getClientIp')
            ->willReturn(null);

        $this->entityManager
            ->expects($this->exactly(3))
            ->method('persist');

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->with();

        $this->cookieLogger->log([
            'analytics'    => 'true',
            'social_media' => 'true',
            'tracking'     => 'false',
        ], 'key-test');
    }

    /**
     * Test CookieLogger:log.
     */
    public function testLogWithoutRequest(): void
    {
        $this->expectException(\RuntimeException::class);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn(null);

        $this->cookieLogger = new CookieLogger($this->registry, $requestStack);
        $this->cookieLogger->log([], 'key-test');
    }
}
