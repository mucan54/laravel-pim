<?php

namespace App\Orchid\Screens\Scope;

use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use App\Models\Scopes;
use Orchid\Support\Facades\Alert;
use Rinvex\Attributes\Models\Attribute;


class ScopeEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'ScopeEditScreen';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'ScopeEditScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Scopes $post): array
    {
        $this->exists = $post->exists;

        if($this->exists){
            $this->name = 'Edit attribute';
        }

        return [
            'scope' => $post
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
        return [ \Orchid\Support\Facades\Layout::rows([
            Input::make('scope.name')
                ->title('Name')
                ->help('Magento USA'),

            Input::make('scope.code')
                ->title('Scope Code')
                ->help('magento_store_view code or any other'),

            Input::make('scope.type')
                ->help('magento, trendyol etc.')
                ->title('Scope Type'),


        ])
        ];
    }

    /**
     * @param Post    $post
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Scopes $post, Request $request)
    {
        $post->fill($request->get('scope'))->save();

        Alert::info('You have successfully created an scope.');

        return redirect()->route('platform.scopes.list');
    }

    /**
     * @param Post $post
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Scopes $post)
    {
        $post->delete();

        Alert::info('You have successfully deleted the scope.');

        return redirect()->route('platform.scopes.list');
    }
}
