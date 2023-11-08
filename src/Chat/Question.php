<?php

declare(strict_types=1);

namespace App\Chat;

use DateTimeInterface;

final class Question
{
    public function __construct(public string $username, public DateTimeInterface $askedAt, public ?string $question)
    {
    }

    public static function create(string $username, DateTimeInterface $askedAt, string $question): self
    {
        return new self($username, $askedAt, $question);
    }
}
