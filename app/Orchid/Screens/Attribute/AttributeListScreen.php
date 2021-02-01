<?php

namespace App\Orchid\Screens\Attribute;

use App\Jobs\MagentoMigrate;
use App\Models\Scopes;
use App\Models\Types\Varchar;
use App\Notifications\MagentoMigrationCompleted;
use App\Orchid\Layouts\AttributeListLayout;
use Grayloon\Magento\Magento;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Alert;
use Rinvex\Attributes\Models\Attribute;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Models\Types\SelectOption;

class AttributeListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Attribute List';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'All attributes';

    protected $attributes=[];
    protected $totalCount;
    protected $stores;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'attributes' => Attribute::paginate()
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
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.attribute.edit')
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        //MagentoMigrate::dispatch(Auth::user());
        //dd(json_decode($magento->api('productAttributes')->show($_GET["isim"])->body()));
        //$this->myhandle(Auth::user());

        //dd(json_decode($magento->api('store')->get('storeViews')->body()));
        return [
            AttributeListLayout::class
        ];
    }

    public function remove(Attribute $post)
    {
        $post->delete();

        Alert::info('You have successfully deleted the attribute.');

        return redirect()->route('platform.attributes.list');
    }

}
