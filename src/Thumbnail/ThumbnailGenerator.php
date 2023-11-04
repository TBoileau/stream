<?php

declare(strict_types=1);

namespace App\Thumbnail;

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
        private readonly string       $thumbnail,
        private readonly array        $fonts,
        private readonly string        $uploads,
    ) {
    }

    public function generate(int $season, int $episode, string $title, string $logo): string
    {
        $episodeFull = sprintf('S%02dE%02d', $season, $episode);

        $thumbnail = $this->imageManager
            ->make($this->thumbnail)
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
                u($title)
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
        $imageInfo = getimagesize($logo);

        /**
         * @var int $imageWidth
         * @var int $imageHeight
         */
        [$imageWidth, $imageHeight] = $imageInfo;

        $categoryW = 490;
        $categoryH = $imageHeight * $categoryW / $imageWidth;

        $category = $this->imageManager->make($logo)->resize($categoryW, $categoryH);

        $thumbnail->insert($category, 'center-left', 270, intval(round(555 - ($categoryH / 2))));

        $filename = sprintf('%s/%s.png', $this->uploads, $episodeFull);

        $thumbnail->save($filename);

        return $filename;
    }
}
