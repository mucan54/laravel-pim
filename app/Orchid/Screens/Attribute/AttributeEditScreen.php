<?php
namespace App\Orchid\Screens\Attribute;

use Rinvex\Attributes\Models\Attribute;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Orchid\Layouts\AttrValues;
use App\Models\Types\SelectOption;

class AttributeEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Creating a new attribute';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Attributes';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Post $post
     *
     * @return array
     */
    public function query(Attribute $post): array
    {
        $this->exists = $post->exists;
        $returnArr=[];
        if($this->exists){
            $this->name = 'Edit attribute';
            $returnArr['attribute']=$post;
            if($post->type="multiselect") {
                $returnArr['values']=SelectOption::where('attribute_id',$post->id)->filters()->defaultSort('id')->paginate();
            }
        }

        return $returnArr;
    }

    /**
     * Button commands.
     *
     * @return Link[]
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
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('attribute.name')
                    ->title('Name')
                    ->placeholder('Size,Color etc.'),

                Input::make('attribute.slug')
                    ->title('Slug')
                    ->placeholder('size')
                    ->help('Without specialchars and spaces.'),

                Select::make('attribute.type')
                    ->title('Attribute Type')
                    ->options([
                        'varchar'   => 'Text',
                        'text'   => 'Long Text',
                        'integer'   => 'Number',
                        'boolean'   => 'Bool',
                        'datetime'   => 'Date Time',
                        'attachments'   => 'Attachment',
                        'color'   => 'Color',
                        'range'   => 'Range',
                        'multiselect'   => 'Multi Select',
                    ]),

                Select::make('attribute.entities.')
                    ->options([
                        'App\Models\Product'   => 'Product'
                    ])
                    ->title('Entities'),

                Quill::make('attribute.description')
                    ->title('Description'),

            ]),
            AttrValues::class
        ];
    }

    /**
     * @param Post    $post
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Attribute $post, Request $request)
    {
        $post->fill($request->get('attribute'))->save();

        Alert::info('You have successfully created an attribute.');

        return redirect()->route('platform.attributes.list');
    }

    /**
     * @param Post $post
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Attribute $post)
    {
        $post->delete();

        Alert::info('You have successfully deleted the attribute.');

        return redirect()->route('platform.attributes.list');
    }
}
