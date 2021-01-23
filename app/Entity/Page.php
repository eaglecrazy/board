<?php

namespace App\Entity;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;


/**
 * App\Entity\Page
 *
 * @property-read \Kalnoy\Nestedset\Collection|Page[] $children
 * @property-read int|null $children_count
 * @property-read Page $parent
 * @property-write mixed $parent_id
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Page d()
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page query()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page defaultOrder()
 * @property string $title
 * @property string $menu_title
 * @property string $slug
 * @property string $content
 * @property string $description
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $_lft
 * @property int $_rgt
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMenuTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 */
class Page extends Model
{
    use NodeTrait;

    protected $table = 'pages';

    protected $guarded = [];

    public function getPath(): string
    {
        return implode('/', array_merge($this->ancestors()->defaultOrder()->pluck('slug')->toArray(), [$this->slug]));
    }

    public function getMenuTitle():string
    {
        return $this->menu_title ?: $this->title;
    }
}
