<?php

use App\Company;
use App\Http\Requests\MakePurchaseRequestRequest;
use App\Item;
use App\Utilities\BuildPhoto;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ItemTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_finds_an_existing_item()
    {
        $existingItem = factory(Item::class)->create();
        $item = Item::findOrCreate($existingItem->id);

        $this->assertEquals($existingItem->id, $item->id);
    }

    /** @test */
    public function it_creates_an_existing_item()
    {
        $item = Item::findOrCreate(null, [
            'sku' => 'abcd1234',
            'brand' => 'bazzo',
            'name' => 'foo',
            'specification' => 'bar',
            'company_id' => factory(Company::class)->create()->id
        ]);

        $this->assertEquals('foo', $item->name);
        $this->assertEquals('bar', $item->specification);
    }

    /** @test */
    public function it_calls_attachPhoto_for_matching_files()
    {
        // Create partial mock with methods to stub out
        $item = m::mock('\App\Item[attachPhoto]');

        // How many times should attachPhoto be called
        $item->shouldReceive('attachPhoto')
             ->times(3);

        $uploadedFiles = [
            m::mock(UploadedFile::class),
            null,
            m::mock(UploadedFile::class),
            'not valid type',
            m::mock(UploadedFile::class),
        ];

        $this->assertEquals($item, $item->handleFiles($uploadedFiles));
    }

    /** @test */
    public function it_attaches_a_photo_to_an_item()
    {
        $file = m::mock(UploadedFile::class, [
            'getClientOriginalName' => 'foo',
            'getClientOriginalExtension' => 'jpg',
            'move' => ''
        ]);

        $photoBuilder = m::mock(BuildPhoto::class);

        $item = factory(Item::class)->create();

        $photo = factory(\App\Photo::class)->create();

        $photoBuilder
            ->shouldReceive('item')
            ->once()
            ->with($item)
            ->andReturn($photo);

        $this->assertEmpty(Item::find($item->id)->photos->all());

        $item->attachPhoto($file, $photoBuilder);

        $this->assertNotEmpty(Item::find($item->id)->photos->all());
        $this->assertEquals($photo->id, Item::find($item->id)->photos()->first()->id);

    }
}
