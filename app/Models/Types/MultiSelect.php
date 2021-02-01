<?php

declare(strict_types=1);

namespace App\Models\Types;

use App\Models\Types\SelectOption;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use App\Orchid\Layouts\Relation;
use Rinvex\Attributes\Models\Attribute;
use Rinvex\Attributes\Models\Value;

/**
 * Rinvex\Attributes\Models\Type\Varchar.
 *
 * @property int                                                $id
 * @property string                                             $content
 * @property int                                                $attribute_id
 * @property int                                                $entity_id
 * @property string                                             $entity_type
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Rinvex\Attributes\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Varchar whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Varchar whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Varchar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Varchar whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Varchar whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Varchar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Varchar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MultiSelect extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'string',
        'attribute_id' => 'integer',
        'entity_id' => 'integer',
        'entity_type' => 'string',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable('attribute_select_values');
        $this->setRules([
            'content' => 'required|string|exists:attribute_select_options,id',
            'attribute_id' => 'required|integer|exists:'.config('rinvex.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|strip_tags|max:150',
        ]);
    }

    public static function renderer($attr,$element){

        //dd(SelectOption::usingLocale('sa_store_view')->all()->toArray());
        return Relation::make($element.'.'.$attr->slug)
            ->setLang('sa_store_view')
            ->fromModel(SelectOption::class, 'content')
            ->where('attribute_id',$attr->id)
            ->help($attr->description)
            ->title($attr->name);
    }

    public function options()
    {

        return $this->hasMany(SelectOption::class,'attribute_id',48);
    }

    public static function magentoCreate($mAttribute,$stores,$optionsArray){
        $value = new Attribute();
        $value->name = $mAttribute->default_frontend_label;
        $value->slug = $mAttribute->attribute_code;
        $value->description = $mAttribute->default_frontend_label;
        $value->type = 'multiselect';
        $value->group = 'magento';
        $value->entities = ['App\Models\Product'];
        foreach ($mAttribute->frontend_labels as $label){
            $value->name = [$stores[$label->store_id] => $label->label];
        }

        $value->save();
        self::createOptions($value->id,$optionsArray,$stores);
    }

    public static function createOptions($id,$array,$stores){
        foreach ($array['en'] as $key => $value){
            if($value->label=="")
                continue;
            $myOption = new SelectOption();
            $myOption->attribute_id=$id;
            $myOption->entity_type= 'App\Models\Product';
            $myOption->content = $value->label;
            foreach ($stores as $store){
                $myOption->content = [$store => $array[$store][$key]['label']];
            }
            $myOption->save();
        }
    }
}
