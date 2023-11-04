<?php

declare(strict_types=1);

namespace App\Thumbnail;

use App\Entity\Live;

interface ThumbnailGeneratorInterface
{
    public function generate(Live $live): string;
}
