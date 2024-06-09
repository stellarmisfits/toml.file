<?php
namespace App\Services;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\BasePathGenerator;

class MediaPathGeneratorService extends BasePathGenerator
{

    /**
     * @param Media $media
     * @return string
     */
    public function getPath(Media $media): string
    {
        return 'media/' . $media->uuid . '/';
    }

    /**
     * @param Media $media
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return 'media/' . $media->uuid . '/conversions/';
    }

    /**
     * @param Media $media
     * @return string
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return 'media/' . $media->uuid . '/responsive-images/';
    }
}
