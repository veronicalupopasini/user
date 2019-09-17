<?php

namespace Esc\User\Entity\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Email
{
    private $email;

    /**
     * Email constructor.
     * @param string $email
     * @throws AssertionFailedException
     */
    public function __construct(string $email)
    {
        Assertion::email($email);
        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
