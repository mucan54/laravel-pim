<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Attributes\Models\Attribute;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Attribute::typeMap([
            'attachments' => \App\Models\Types\Attachment::class,
            'text' =>\App\Models\Types\Text::class,
            'boolean' =>\App\Models\Types\Boolean::class,
            'integer' =>\App\Models\Types\Integer::class,
            'varchar' =>\App\Models\Types\Varchar::class,
           'datetime' => \App\Models\Types\Datetime::class,
           'color' => \App\Models\Types\Color::class,
           'range' => \App\Models\Types\Range::class,
           'multiselect' => \App\Models\Types\MultiSelect::class,
        ]);

        app('rinvex.attributes.entities')->push(\Rinvex\Attributes\Models\Type\Integer::class);
    }
}
