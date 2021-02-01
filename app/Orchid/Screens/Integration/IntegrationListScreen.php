<?php

namespace App\Orchid\Screens\Integration;

use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Rinvex\Attributes\Models\Attribute;
use App\Models\Integration;
use App\Orchid\Layouts\IntegrationLayout;

class IntegrationListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'IntegrationListScreen';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'IntegrationListScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'integrations' => Integration::paginate()
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
                ->route('platform.integration.edit')
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
            IntegrationLayout::class
        ];
    }
}
