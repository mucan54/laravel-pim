<?php

namespace App\Orchid\Screens\Integration;

use App\Models\Integration;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class IntegrationEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'IntegrationEditScreen';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'IntegrationEditScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Integration $post): array
    {
        $this->exists = $post->exists;

        if($this->exists){
            $this->name = 'Edit Integration';
        }

        return [
            'integration' => $post
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
            Button::make('Run')
                ->icon('control-play')
                ->method('run')
                ->canSee($this->exists),

            Button::make('Create attribute')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|Layout[]
     */
    public function layout(): array
    {
        return [ Layout::rows([
            Input::make('integration.name')
                ->title('Name'),

            Input::make('integration.type')
                ->title('Type'),

            Select::make('integration.source_type')
                ->title('Attribute Type')
                ->options([
                    'input'   => 'Input Source',
                    'output'   => 'Ouyput Target'
                ]),

            Input::make('integration.token')
                ->title('Token'),
            Input::make('integration.url')
                ->title('URL'),

        ])];
    }

    /**
     * @param Post    $post
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Integration $post, Request $request)
    {
        $post->fill($request->get('integration'))->save();

        Alert::info('You have successfully created an integration.');

        return redirect()->route('platform.attributes.list');
    }

    /**
     * @param Post $post
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Integration $post)
    {
        $post->delete();

        Alert::info('You have successfully deleted the integration.');

        return redirect()->route('platform.attributes.list');
    }
}
