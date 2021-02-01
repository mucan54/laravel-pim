<?php

namespace App\Models\Types;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Rinvex\Cacheable\CacheableEloquent;
use Rinvex\Attributes\Traits\Attributable;
use Orchid\Attachment\Attachable;
use Rinvex\Support\Traits\HasTranslations;
use Orchid\Filters\Filterable;

class SelectOption extends Model
{
    use HasTranslations, AsSource, Filterable;

    protected $table = "attribute_select_options";

    protected $fillable = [
        'content',
        'attribute_id',
        'entity_id',
        'entity_type'
    ];

    protected $translatable = ['content'];

    protected $allowedFilters = [
        'content',
        'attribute_id',
        'entity_id',
        'entity_type'
    ];

    protected $allowedSorts = [
        'content',
        'attribute_id',
        'entity_id',
        'entity_type'
    ];


}
