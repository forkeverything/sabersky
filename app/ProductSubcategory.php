<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductSubcategory
 *
 * @property integer $id
 * @property string $name
 * @property string $label
 * @property integer $product_category_id
 * @property-read \App\ProductCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Item[] $items
 * @method static \Illuminate\Database\Query\Builder|\App\ProductSubcategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProductSubcategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProductSubcategory whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProductSubcategory whereProductCategoryId($value)
 * @mixin \Eloquent
 */
class ProductSubcategory extends Model
{
    public $timestamps = false;

    /**
     * Every subcategory belongs to a broader category
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Subcategory can contain many individual Items
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
