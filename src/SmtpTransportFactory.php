<?php

namespace Trowski\AsyncSwiftMailer;

use Swift_SmtpTransport as SwiftSmtpTransport;
use Swift_Transport as SwiftTransport;

final class SmtpTransportFactory implements TransportFactory
{
    /** @var string */
    private $host;

    /** @var int */
    private $port;

    /** @var bool */
    private $encrypted = false;

    /** @var string|null */
    private $username;

    /** @var string|null */
    private $password;

    /** @var string|null */
    private $authMode;

    /** @var string */
    private $localDomain = '[127.0.0.1]';

    public function __construct(string $host = 'localhost', int $port = 25)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function createTransport(): SwiftTransport
    {
        $transport = new SwiftSmtpTransport;
        $transport->setHost($this->host);
        $transport->setPort($this->port);
        $transport->setLocalDomain($this->localDomain);

        if ($this->username !== null) {
            $transport->setUsername($this->username);
        }

        if ($this->password !== null) {
            $transport->setPassword($this->password);
        }

        if ($this->authMode !== null) {
            $transport->setAuthMode($this->authMode);
        }

        if ($this->encrypted) {
            $transport->setEncryption('tls');
        }

        return $transport;
    }

    public function getKey(): string
    {
        return \sha1(
            $this->host
            . $this->port
            . $this->username
            . $this->password
            . $this->authMode
            . $this->localDomain
            . ($this->encrypted ? '1' : '0')
        );
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function withHost(string $host): self
    {
        $clone = clone $this;
        $clone->host = $host;
        return $clone;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function withPort(int $port): self
    {
        $clone = clone $this;
        $clone->port = $port;
        return $clone;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function withUsername(string $username): self
    {
        $clone = clone $this;
        $clone->username = $username;
        return $clone;
    }

    public function withoutUsername(): self
    {
        $clone = clone $this;
        $clone->username = null;
        return $clone;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function withPassword(string $password): self
    {
        $clone = clone $this;
        $clone->password = $password;
        return $clone;
    }

    public function withoutPassword(): self
    {
        $clone = clone $this;
        $clone->password = null;
        return $clone;
    }

    public function isEncryptionEnabled(): bool
    {
        return $this->encrypted;
    }

    public function withEncryption(): self
    {
        $clone = clone $this;
        $clone->encrypted = true;
        return $clone;
    }

    public function withoutEncryption(): self
    {
        $clone = clone $this;
        $clone->encrypted = false;
        return $clone;
    }

    public function getAuthMode(): ?string
    {
        return $this->authMode;
    }

    public function withAuthMode(string $authMode): self
    {
        $clone = clone $this;
        $clone->authMode = $authMode;
        return $clone;
    }

    public function getLocalDomain(): string
    {
        return $this->localDomain;
    }

    public function withLocalDomain(string $domain): self
    {
        $clone = clone $this;
        $clone->localDomain = $domain;
        return $clone;
    }
}
