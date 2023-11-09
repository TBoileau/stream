<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class Message
{
    private const QUESTION_PATTERN = '/!question (?<question>.+)$/';

    public DateTimeInterface $askedAt;

    public ?string $question = null;
    private function __construct(
        #[NotBlank]
        public string $username,
        #[NotBlank]
        #[Regex(pattern: self::QUESTION_PATTERN)]
        public string $message
    ) {
        $this->askedAt = new DateTimeImmutable();
    }

    public static function create(string $username, string $message): self
    {
        return new self($username, $message);
    }

    public function toQuestion(): Question
    {
        /** @var array{question: string} $matches */
        preg_match(self::QUESTION_PATTERN, $this->message, $matches);

        return Question::create($this->username, $this->askedAt, $matches['question']);
    }
}
