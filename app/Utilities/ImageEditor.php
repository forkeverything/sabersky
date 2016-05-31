<?php


namespace App\Utilities;


use Intervention\Image\Facades\Image;

class ImageEditor
{

    /**
     * Resize an image at a path to given dimensions
     *
     * @param $width
     * @param $height
     * @param $path
     */
    public function resize($width, $height, $path)
    {
        Image::make($path)
             ->resize($width, $height, function ($constraint) {
                 $constraint->upsize();
                 $constraint->aspectRatio();
             })
             ->save($path);
    }

    public function thumbnailItem($src, $dest)
    {
        Image::make($src)
            ->fit(125, 125, function ($constraint) {
                $constraint->upsize();
            })
            ->save($dest);
    }

}