<?php

namespace Hyqo\Email;

use Hyqo\Email\Typo\FirstLevelTypo;
use Hyqo\Email\Typo\SecondLevelTypo;

class Email
{
    private $address;

    private $domain;

    private $domainParts;

    /** @var bool */
    private $isValid;

    public function __construct(string $email)
    {
        $email = trim($email);

        if ($this->isValid = filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
            [$this->address, $this->domain] = explode('@', $email);
            $this->domainParts = array_reverse(explode('.', $this->domain));
        }
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function fixTypo(): ?string
    {
        if (!$this->isValid) {
            return null;
        }

        $this->domainParts[0] = $this->replace($this->domainParts[0], FirstLevelTypo::REPLACEMENTS);

        for ($i = 1, $count = count($this->domainParts); $i < $count; $i++) {
            $this->domainParts[$i] = $this->replace($this->domainParts[$i], SecondLevelTypo::REPLACEMENTS);
        }

        $this->domain = implode('.', array_reverse($this->domainParts));

        return sprintf('%s@%s', $this->address, $this->domain);
    }

    protected function replace(string $value, array $replacements): string
    {
        foreach ($replacements as $right => $wrongList) {
            foreach ($wrongList as $wrong) {
                if ($value === $wrong) {
                    return $right;
                }
            }
        }

        return $value;
    }
}
