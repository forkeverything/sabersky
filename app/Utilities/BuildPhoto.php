<?php


namespace App\Utilities;

use App\Company;
use App\Item;
use App\Photo;
use App\Photos\Editor;
use App\Photos\Thumbnail;
use App\Profiles\Announcement;
use App\User;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BuildPhoto
{

    /**
     * Root folder to save uploads
     *
     * @var string
     */
    protected $baseDir = 'uploads';

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var ImageEditor
     */
    private $imageEditor;


    /**
     * Instantiate a BuildPhoto class to handle photos / images from
     * forms.
     *
     * @param UploadedFile $file
     * @param ImageEditor $imageEditor
     */
    public function __construct(UploadedFile $file, ImageEditor $imageEditor = null)
    {
        $this->file = $file;
        $this->imageEditor = $imageEditor ?: new ImageEditor;
    }

    /**
     * Make a file name, based on the uploaded file.
     *
     * @return string
     */
    protected function makeFileName()
    {
        $name = sha1(
            time() . $this->file->getClientOriginalName()
        );

        $extension = $this->file->getClientOriginalExtension();

        return "{$name}.{$extension}";
    }

    /**
     * Process images to be attached to an announcement and
     * returns an instance of Photo.
     *
     * @param Item $item
     * @return Photo
     */
    public function item(Item $item)
    {
        $directory = $this->baseDir . '/company/' . encode($item->company()->id) . '/items/' . encode($item->id);
        $name = $this->makeFileName();
        $path = $directory . '/' . $name;
        $thumbnail_path = $directory . '/tn_' . $name;
        // Move the file
        $this->file->move($directory, $name);
        // resize it
        $this->imageEditor->resize640($path);
        // Make a thumbnail for it
        $this->imageEditor->thumbnailItem($path, $thumbnail_path);
        // Return photo
        return new Photo(['name' => $name, 'path' => $path, 'thumbnail_path' => $thumbnail_path]);
    }



}
