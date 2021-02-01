<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Types\SelectOption;
use App\Models\Scopes;

class AttrValues extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'values';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {

        return [
            TD::make('content','Value')->filter(TD::FILTER_TEXT),
            TD::make('sa_store_view')->render(function (SelectOption $user) {
                return $user->getTranslation('content', 'sa_store_view');
            })->filter(TD::FILTER_TEXT),
            TD::make('de_store_view')->render(function (SelectOption $user) {
                return $user->getTranslation('content', 'de_store_view');
            })->filter(TD::FILTER_TEXT),
            TD::make('qa_store_view')->render(function (SelectOption $user) {
                return $user->getTranslation('content', 'qa_store_view');
            })->filter(TD::FILTER_TEXT),

            TD::make('trendyol')->render(function (SelectOption $user) {
                return $user->getTranslation('content', 'trendyol');
            })->filter(TD::FILTER_TEXT),
        ];
    }
}
