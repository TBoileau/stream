<?php

declare(strict_types=1);

namespace App\Schedule;

use App\Entity\Schedule;

interface ScheduleGeneratorInterface
{
    public function generate(Schedule $schedule): string;
}
