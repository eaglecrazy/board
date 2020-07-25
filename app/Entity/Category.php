<?php

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Entity\Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property-read \Kalnoy\Nestedset\Collection|\App\Entity\Category[] $children
 * @property-read int|null $children_count
 * @property-read \App\Entity\Category|null $parent
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Category d()
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Entity\Category newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Entity\Category newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Entity\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Category whereSlug($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Attribute[] $attributes
 * @property-read int|null $attributes_count
 */
class Category extends Model
{
    use NodeTrait;

    protected $table = 'advert_categories';
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function attributes(){
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }
}
