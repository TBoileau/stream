<?php

declare(strict_types=1);

namespace App\UseCase\Chat;

use App\Entity\Question;

interface QuestionProviderInterface
{
    public function current(): ?Question;
}
