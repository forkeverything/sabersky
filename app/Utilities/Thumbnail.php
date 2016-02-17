<?php


namespace App\Utilities;


use Intervention\Image\Facades\Image;

class Thumbnail
{

    /**
     * Creates a 200 x 200 thumbnail
     * to use a company profile picture.
     *
     * @param $src
     * @param $destination
     */
    public function makeProfile($src, $destination)
    {
        Image::make($src)
            ->fit(200, 200)
            ->save($destination);
    }

    /**
     * Creates a 250 x 250 thumbnail
     * for announcement images.
     *
     * @param $src
     * @param $dest
     */
    public function makeAnnouncement($src, $dest)
    {
        Image::make($src)
            ->fit(250, 250, function ($constraint) {
                $constraint->upsize();
            })
            ->save($dest);
    }

}