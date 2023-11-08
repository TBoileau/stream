<?php

declare(strict_types=1);

namespace App\Chat;

use Symfony\Component\Validator\Exception\ValidationFailedException;

interface StoreMessageInterface
{
    /**
     * @throws ValidationFailedException
     */
    public function __invoke(Message $message): void;
}
