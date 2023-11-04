<?php

declare(strict_types=1);

namespace App\ImageManager;

use Intervention\Image\ImageManager;

final class ImageManagerFactory
{
    public static function create(string $driver): ImageManager
    {
        return new ImageManager(['driver' => $driver]);
    }
}
