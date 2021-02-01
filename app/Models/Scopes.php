<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Scopes extends Model
{
    use HasFactory,AsSource;

    protected $fillable = [
        'name',
        'code',
        'type'
    ];

    protected $allowedFilters = [
        'name',
        'code',
        'type'
    ];

    protected $allowedSorts = [
        'name',
        'code',
        'type'
    ];
}
