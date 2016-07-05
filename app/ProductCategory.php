<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductCategory
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductSubcategory[] $subcategories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Item[] $items
 * @method static \Illuminate\Database\Query\Builder|\App\ProductCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProductCategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProductCategory whereLabel($value)
 * @mixin \Eloquent
 */
class ProductCategory extends Model
{
    public $timestamps = false;

    /**
     * Each category can contain many subcategories
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategories()
    {
        return $this->hasMany(ProductSubcategory::class);
    }

    /**
     * A Product Category contains many Subcategories which in turn contain many Items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function items()
    {
        return $this->hasManyThrough(Item::class, ProductSubcategory::class);
    }

}
