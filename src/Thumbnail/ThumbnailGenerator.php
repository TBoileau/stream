<?php

declare(strict_types=1);

namespace App\Thumbnail;

use App\Entity\Live;
use Intervention\Image\AbstractFont;
use Intervention\Image\ImageManager;
use function Symfony\Component\String\u;

class ThumbnailGenerator implements ThumbnailGeneratorInterface
{
    /**
     * @param array<array-key, string> $fonts
     */
    public function __construct(
        private readonly ImageManager $imageManager,
        private readonly string       $thumbnailPath,
        private readonly array        $fonts,
        private readonly string       $uploads,
    )
    {
    }

    public function generate(Live $live): string
    {
        $episodeFull = sprintf('S%02dE%02d', $live->season, $live->episode);

        $image = $this->imageManager
            ->make($this->thumbnailPath)
            ->text(
                $episodeFull,
                1120,
                440,
                fn(AbstractFont $font) => $font
                    ->file($this->fonts['thunder'])
                    ->size(96)
                    ->color('#FFFFFF')
                    ->align('center')
                    ->valign('center')
            )
            ->text(
                u($live->title)
                    ->upper()
                    ->wordwrap(20, "\n", false)->toString(),
                918,
                520,
                fn(AbstractFont $font) => $font
                    ->file($this->fonts['thunder'])
                    ->size(128)
                    ->color('#FFFFFF')
                    ->align('left')
                    ->valign('top')
            );

        /** @var array<int, int> $imageInfo */
        $imageInfo = getimagesize($live->logo);

        /**
         * @var int $imageWidth
         * @var int $imageHeight
         */
        [$imageWidth, $imageHeight] = $imageInfo;

        $categoryW = 490;
        $categoryH = $imageHeight * $categoryW / $imageWidth;

        $category = $this->imageManager->make($live->logo)->resize($categoryW, $categoryH);

        $image->insert($category, 'center-left', 270, intval(round(555 - ($categoryH / 2))));

        $filename = sprintf('%s/%s.png', $this->uploads, $episodeFull);

        $image->save($filename);

        return $filename;
    }
}
