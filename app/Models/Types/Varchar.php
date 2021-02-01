<?php

declare(strict_types=1);

namespace App\Models\Types;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
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
class Varchar extends Value
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

        $this->setTable(config('rinvex.attributes.tables.attribute_varchar_values'));
        $this->setRules([
            'content' => 'required|string|max:150',
            'attribute_id' => 'required|integer|exists:'.config('rinvex.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|strip_tags|max:150',
        ]);
    }

    public static function renderer($attr,$element){

        return Input::make($element.'.'.$attr->slug)
            ->type('text')
            ->help($attr->description)
            ->title($attr->name);
    }

    public static function magentoCreate($mAttribute,$stores){
        $value = new Attribute();
        if(!isset($mAttribute->default_frontend_label)){
           return;
        }
        $value->name = $mAttribute->default_frontend_label;
        $value->slug = $mAttribute->attribute_code;
        $value->description = $mAttribute->default_frontend_label;
        $value->type = 'varchar';
        $value->group = 'magento';
        $value->entities = ['App\Models\Product'];
        foreach ($mAttribute->frontend_labels as $label){
            $value->name = [$stores[$label->store_id] => $label->label];
        }
        $value->save();
    }
}
