<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
