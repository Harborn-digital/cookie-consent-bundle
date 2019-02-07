<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Cookie;

use ConnectHolland\CookieConsentBundle\Entity\CookieConsentLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

class CookieLogger
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Request
     */
    private $request;

    public function __construct(RegistryInterface $registry, Request $request)
    {
        $this->entityManager = $registry->getEntityManagerForClass(CookieConsentLog::class);
        $this->request       = $request;
    }

    /**
     * Logs users preferences in database.
     */
    public function log(array $categories): void
    {
        foreach ($categories as $category => $value) {
            $this->persistCookieConsentLog($category, $value);
        }

        $this->entityManager->flush();
    }

    protected function persistCookieConsentLog(string $category, string $value): void
    {
        $cookieConsentLog = (new CookieConsentLog())
            ->setIpAddress($this->request->getClientIp() ?? 'unknown')
            ->setCookieName($category)
            ->setCookieValue($value)
            ->setTimestamp(new \DateTime());

        $this->entityManager->persist($cookieConsentLog);
    }
}
