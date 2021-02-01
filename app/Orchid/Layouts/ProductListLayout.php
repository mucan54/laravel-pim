<?php

namespace App\Orchid\Layouts;

use App\Models\Product;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ProductListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('Name')
                ->render(function (Product $post) {
                    return Link::make($post->sku)
                        ->route('platform.product.edit', $post);
                }),


        ];
    }
}
