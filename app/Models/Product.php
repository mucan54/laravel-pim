<?php

namespace App\Models;

use App\Orchid\Presenters\ProductPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Attributes\Traits\Attributable;
use Orchid\Attachment\Attachable;
use App\Models\ProductVariant;

class Product extends Model
{
    use HasFactory, AsSource, Attributable, Attachable;

    protected $with = ['eav'];

    protected $table = "product";

    protected $fillable = [
        'sku',
        'type',
        'visibility'
    ];

    protected $allowedFilters = [
        'sku',
        'type',
        'visibility'
    ];

    protected $allowedSorts = [
        'sku',
        'type',
        'visibility'
    ];


    public function presenter(): ProductPresenter
    {
        return new ProductPresenter($this);
    }

    public function variants(){

        return $this->belongsToMany(Product::class)->using(ProductVariant::class);
    }

}
