<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

final class Live
{
    public DateTimeImmutable $startTime;

    public string $title;

    public ?int $season = null;

    public ?int $episode = null;

    public ?string $logo = null;
}
