<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cookieconsent_log")]
class CookieConsentLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;


    #[ORM\Column(type: 'string', length: 255)]
    protected string $ipAddress;


    #[ORM\Column(type: 'string', length: 255)]
    protected string $consentKey;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $cookieName;


    #[ORM\Column(type: 'string', length: 1024)]
    protected string $cookieValue;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $timestamp;

    public function getId(): int
    {
        return $this->id;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setConsentKey(string $consentKey): self
    {
        $this->consentKey = $consentKey;

        return $this;
    }

    public function getConsentKey(): string
    {
        return $this->consentKey;
    }

    public function setCookieName(string $cookieName): self
    {
        $this->cookieName = $cookieName;

        return $this;
    }

    public function getCookieName(): string
    {
        return $this->cookieName;
    }

    public function setCookieValue(string $cookieValue): self
    {
        $this->cookieValue = $cookieValue;

        return $this;
    }

    public function getCookieValue(): string
    {
        return $this->cookieValue;
    }

    public function setTimestamp(DateTime $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }
}
