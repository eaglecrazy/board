<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

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
 */
class Region extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];
    public $timestamps = false;


    public function parent(){
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function children(){
        return $this->hasMany(static::class, 'parent_id', 'id');
    }
}
