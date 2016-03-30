<?php

namespace App\Utilities;

use App\Item;
use App\Photo;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BuildPhotoTest extends \TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_builds_an_item_photo()
    {
        $file = m::mock(UploadedFile::class, [
            'getClientOriginalName' => 'foo',
            'getClientOriginalExtension' => 'jpg'
        ]);

        $editor = m::mock(ImageEditor::class);

        $photoBuilder = (new \App\Utilities\BuildPhoto($file, $editor));

        $item = factory(Item::class)->create();
        $item['company'] = (object)[
            'id' => 888
        ];

        $directory = 'uploads/company/' . encode(888) . '/items/' . encode($item->id);

        $file->shouldReceive('move')
             ->once()
             ->with($directory, 'nowfoo.jpg');       // With what arguments

        $editor->shouldReceive('resize640')
               ->once()
               ->with($directory . '/nowfoo.jpg');

        $editor->shouldReceive('thumbnailItem')
               ->once()
               ->with($directory . '/nowfoo.jpg', $directory . '/tn_nowfoo.jpg');

        $photo = $photoBuilder->item($item);

        $this->assertTrue($photo instanceof Photo);
        $this->assertEquals('nowfoo.jpg', $photo->name);
        $this->assertEquals('/' . $directory . '/nowfoo.jpg', $photo->path);
        $this->assertEquals('/' . $directory . '/tn_nowfoo.jpg', $photo->thumbnail_path);
    }
}


/**
 * Override the class's time function.
 *
 * @return string
 */
function time()
{
    return 'now';
}

function sha1($path)
{
    return $path;
}