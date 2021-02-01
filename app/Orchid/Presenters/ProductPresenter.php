<?php

namespace App\Orchid\Presenters;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Contracts\Cardable;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Color;
use Orchid\Support\Presenter;
use App\Models\Types\Attachment;
use App\Models\Scopes;
use Orchid\Attachment\Models\Attachment as OrchidAttachment;

class ProductPresenter extends Presenter implements Cardable
{

    public function title(): string
    {
        return $this->entity->sku;
    }

    public function description(): string
    {
        return Select::make('user')
            ->fromModel(Scopes::class, 'name')
            ->empty('Default', 0);
    }

    public function image(): ?string
    {
        $img = "";
        $id = Attachment::find($this->entity->id);
        if($id){
            $id=$id->content[0];
            $img = OrchidAttachment::find($id)->url();
        }
        return $img;
    }

    public function color(): ?Color
    {
        return $this->entity->amount > 0
            ? Color::SUCCESS()
            : Color::DANGER();
    }
}
