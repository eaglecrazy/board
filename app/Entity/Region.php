<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Entity\Region
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Region[] $children
 * @property-read int|null $children_count
 * @property-read \App\Entity\Region|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region whereSlug($value)
 * @mixin \Eloquent
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Region roots()
 */
class Region extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];
    public $timestamps = false;


    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function getAddress(): string
    {
        return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
    }

    public function scopeRoots(Builder $query)
    {
        return $query->where('parent_id', null);
    }

    public function getPath(): string
    {
        return ($this->parent ? $this->parent->getPath() . '/' : '') . $this->slug;
    }
}
