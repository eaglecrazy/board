<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Attribute
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $type
 * @property int $required
 * @property array $variants
 * @property int $sort
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Attribute whereVariants($value)
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    public const TYPE_STRING = 'string';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT = 'float';

    protected $table = 'advert_attributes';

    public $timestamps = false;

    protected $fillable = ['name', 'type', 'required', 'default', 'variants', 'sort'];

    protected $casts = ['variants' => 'array'];

    public static function typesList(): array
    {
        return [
            self::TYPE_STRING => 'string',
            self::TYPE_INTEGER => 'integer',
            self::TYPE_FLOAT => 'float',
        ];
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }
}
