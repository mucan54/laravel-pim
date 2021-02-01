<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;
use Rinvex\Attributes\Traits\Attributable;

class Integration extends Model
{
    use HasFactory, AsSource, Attributable, Attachable;

    protected $table = "integration";

    protected $fillable = [
        'name',
        'token',
        'type',
        'url',
        'source_type'
    ];

    protected $allowedFilters = [
        'name',
        'token',
        'type',
        'url',
        'source_type'
    ];

    protected $allowedSorts = [
        'name',
        'token',
        'type',
        'url',
        'source_type'
    ];
}
