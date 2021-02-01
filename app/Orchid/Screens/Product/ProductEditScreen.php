<?php
namespace App\Orchid\Screens\Product;

use App\Models\Product;
use Orchid\Screen\Layouts\Card;
use Rinvex\Attributes\Models\Attribute;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ProductEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Creating a new post';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Blog posts';

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
    public function query(Product $post): array
    {
        $this->exists = $post->exists;
        $attributes = Attribute::all();
        $this->attributes=$attributes;

        if($this->exists){
            $this->name = 'Edit post';
        }

        return [
            'product' => $post,
            'productpresenter' => $post->presenter(),
            'attributes' => $attributes
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create post')
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
        $attributes=[];
        foreach ($this->attributes as $attr){
            //dd($attr);
            //dd(app($attr->getTypeModel($attr->type))->where('id', $attr->id)->first());
            $attributes[]= app($attr->getTypeModel($attr->type))::renderer($attr,'product');
        }

        return [
            new Card('productpresenter'),
            Layout::tabs([
            'Product Information' => [
            Layout::rows([
                Input::make('product.sku')
                    ->title('Title')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this post.'),
            ])],
            'Attributes'      => [
                Layout::rows($attributes)
            ]
            ]),
        ];
    }

    /**
     * @param Post    $post
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Product $post, Request $request)
    {

        $post->fill($request->get('product'))->save();

        Alert::info('You have successfully created an post.');

        return redirect()->route('platform.products.list');
    }

    /**
     * @param Post $post
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Product $post)
    {
        $post->delete();

        Alert::info('You have successfully deleted the post.');

        return redirect()->route('platform.products.list');
    }
}
