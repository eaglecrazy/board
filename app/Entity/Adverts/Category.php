<?php

namespace App\Entity\Adverts;



use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;


/**
 * App\Entity\Adverts\Category
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Adverts\Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \Kalnoy\Nestedset\Collection|\App\Entity\Adverts\Category[] $children
 * @property-read int|null $children_count
 * @property-read \App\Entity\Adverts\Category|null $parent
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category d()
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Entity\Adverts\Category newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Entity\Adverts\Category newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|\App\Entity\Adverts\Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Category whereIsRoot()
 * @mixin \Eloquent
 */
class Category extends Model
{
    use NodeTrait;

    protected $table = 'advert_categories';
    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'parent_id'];

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }

    public function parentAttributes(): array
    {
        $name = $this->name;
        $q = $this->parent ? $this->parent->allAttributes() : [];
        return $q;
    }

    public function allAttributes(): array
    {
        $sort = function ($f1, $f2) {
            if ($f1->sort < $f2->sort) return -1;
            elseif ($f1->sort > $f2->sort) return 1;
            else return 0;
        };

        $parent = $this->parentAttributes();
        $own = $this->attributes()->orderBy('sort')->getModels();
        $result = array_merge($parent, $own);
        uasort($result, $sort);
        return $result;
    }

    public function getPath(): string
    {
        return implode('/', array_merge($this->ancestors()->defaultOrder()->pluck('slug')->toArray(), [$this->slug]));
    }
}
