<?php

declare(strict_types=1);

namespace App\Models\Types;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Rinvex\Attributes\Models\Value;

/**
 * Rinvex\Attributes\Models\Type\Integer.
 *
 * @property int                                                $id
 * @property int                                                $content
 * @property int                                                $attribute_id
 * @property int                                                $entity_id
 * @property string                                             $entity_type
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Rinvex\Attributes\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Integer whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Integer whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Integer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Integer whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Integer whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Integer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Attributes\Models\Type\Integer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attachment extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'array',
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

        $this->setTable('attribute_attachment_values');
        $this->setRules([
            'content' => 'required|array',
            'attribute_id' => 'required|integer|exists:'.config('rinvex.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string|strip_tags|max:150',
        ]);
    }


    public static function renderer($attr,$element){

        return Upload::make($element.'.'.$attr->slug)
            ->help($attr->description)
            ->title($attr->name);
    }
}
