<?php

namespace App\Orchid\Screens\Scope;

use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Rinvex\Attributes\Models\Attribute;
use App\Models\Scopes;
use App\Orchid\Layouts\ScopeListLayout;

class ScopeListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'ScopeListScreen';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'ScopeListScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'scopes' => Scopes::paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.scope.edit')
        ];
    }

    /**
     * Views.
     *
     * @return string[]|Layout[]
     */
    public function layout(): array
    {
        return [
            ScopeListLayout::class
        ];
    }
}
