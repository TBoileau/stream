<?php

declare(strict_types=1);

namespace App\Thumbnail;

interface ThumbnailGeneratorInterface
{
    public function generate(int $season, int $episode, string $title, string $logo): string;
}
