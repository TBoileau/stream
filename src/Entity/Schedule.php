<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use IntlDateFormatter;
use Stringable;

final class Schedule implements Stringable
{
    public DateTimeImmutable $startedAt;

    public DateTimeImmutable $endedAt;

    /**
     * @var array<array-key, Live>
     */
    public array $lives = [];


    public function __toString(): string
    {
        $start = (int) $this->startedAt->format('j');

        $end = (int) $this->endedAt->format('j');

        return sprintf(
            'du %d au %d %s',
            $start,
            $end,
            IntlDateFormatter::formatObject($this->startedAt, 'MMMM', 'fr_FR')
        );
    }

    public function getLiveByDate(DateTimeImmutable $date): ?Live
    {
        foreach ($this->lives as $live) {
            if ($live->startTime->format('Y-m-d') === $date->format('Y-m-d')) {
                return $live;
            }
        }

        return null;
    }
}
