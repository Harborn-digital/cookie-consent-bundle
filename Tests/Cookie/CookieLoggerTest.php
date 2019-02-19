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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

class CookieLoggerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $request;

    /**
     * @var MockObject
     */
    private $entityManager;

    public function setUp()
    {
        $this->request       = $this->createMock(Request::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $registry = $this->createMock(RegistryInterface::class);
        $registry
            ->expects($this->once())
            ->method('getEntityManagerForClass')
            ->with(CookieConsentLog::class)
            ->willReturn($this->entityManager);

        $this->cookieLogger = new CookieLogger($registry, $this->request);
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
}
