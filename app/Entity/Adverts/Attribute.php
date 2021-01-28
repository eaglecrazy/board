<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

/**
 * App\Entity\Adverts\Attribute
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $type
 * @property int $required
 * @property array $variants
 * @property int $sort
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Attribute whereVariants($value)
 * @mixin \Eloquent
 * @property-read Category $category
 */
class Attribute extends Model
{
    public const TYPE_STRING = 'string';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT = 'float';
    public const TYPE_BOOL = 'bool';

    protected $table = 'advert_attributes';

    public $timestamps = false;

    protected $fillable = ['name', 'type', 'required', 'default', 'variants', 'sort', 'category_id'];

    protected $casts = ['variants' => 'array'];

    public static function typesList(): array
    {
        return [
            self::TYPE_STRING => 'строковый',
            self::TYPE_INTEGER => 'целые числа',
            self::TYPE_FLOAT => 'дробные числа',
            self::TYPE_BOOL => 'да/нет',
        ];
    }

    public function isBool(): bool
    {
        return $this->type === self::TYPE_BOOL;
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isNumber(): bool
    {
        return $this->isInteger() || $this->isFloat();
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 1;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getValidationRules(): array
    {
        $rules = [
            $this->required ? 'required' : 'nullable',
        ];
        if ($this->isInteger()) {
            $rules[] = 'integer';
        } elseif ($this->isFloat()) {
            $rules[] = 'numeric';
        } elseif ($this->isBool()) {
            $rules[] = 'nullable';
            $rules[] = 'string';
            $rules[] = 'max:3';
        } else {
            $rules[] = 'string';
            $rules[] = 'max:255';
            $rules[] = 'min:2';
        }
        if ($this->isSelect()) {
            $rules[] = Rule::in($this->variants);
        }
        return $rules;
    }
}
