<?php

namespace App\Entity\Adverts\Advert;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Adverts\Advert\Value
 *
 * @property int $advert_id
 * @property int $attribute_id
 * @property string $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\AttributeValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\AttributeValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\AttributeValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\AttributeValue whereAdvertId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\AttributeValue whereValue($value)
 * @mixin \Eloquent
 */
class AttributeValue extends Model
{
    protected $table = 'advert_attributes_values';
    public $timestamps = false;
    protected $fillable = ['attribute_id', 'value'];
}
