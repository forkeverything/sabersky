<?php


namespace App\Utilities;


use Intervention\Image\Facades\Image;

class Editor
{
    /**
     * Accepts the path to an image and
     * re-sizes it to be 640x640.
     *
     * @param $path
     */
    public function resize640($path)
    {
        Image::make($path)
            ->resize(640, 640, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            })
            ->save($path);
    }

}