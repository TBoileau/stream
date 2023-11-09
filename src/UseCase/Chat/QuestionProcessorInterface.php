<?php

declare(strict_types=1);

namespace App\UseCase\Chat;

use App\Entity\Question;
use Symfony\Component\Validator\Exception\ValidationFailedException;

interface QuestionProcessorInterface
{
    /**
     * @throws ValidationFailedException
     */
    public function save(Question $question): void;

    public function next(): void;

    public function previous(): void;
}
