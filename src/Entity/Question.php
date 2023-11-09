<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    public ?int $id = null;

    #[Column]
    public bool $answered = false;

    #[Column]
    public bool $current = false;

    private function __construct(
        #[Column]
        public string $username,
        #[Column(type: Types::DATETIME_IMMUTABLE)]
        public DateTimeInterface $askedAt,
        #[Column(type: Types::TEXT)]
        public string $content,
    ) {
    }

    public static function create(string $username, DateTimeInterface $askedAt, string $content): self
    {
        return new self($username, $askedAt, $content);
    }
}
