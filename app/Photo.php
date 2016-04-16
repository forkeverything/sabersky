<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

/**
 * App\Photo
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $path
 * @property string $thumbnail_path
 * @property integer $model_id
 * @property string $model_type
 */
class Photo extends Model
{

    /**
     * Mass-assignable fields for photos
     * table.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'thumbnail_path',
        'model_id',
        'model_type'
    ];

    public function getPathAttribute($property)
    {
        return '/' . $property;
    }

    public function getThumbnailPathAttribute($property)
    {
        return '/' . $property;
    }


    /**
     * Leverages Laravel's File System to physically
     * remove the image files. Need to remove the
     * leading '/' appended to path.
     * 
     * @return $this
     */
    public function deletePhysicalFiles()
    {
        \File::delete(ltrim($this->path, '/'));
        \File::delete(ltrim($this->thumbnail_path, '/'));
        return $this;
    }
}
