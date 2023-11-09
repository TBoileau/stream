<?php

declare(strict_types=1);

namespace App\UseCase\Schedule;

use App\Entity\Schedule;

interface ScheduleGeneratorInterface
{
    public function generate(Schedule $schedule): string;
}
