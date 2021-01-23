<?php

namespace App\Entity\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\User\SocialNetwork
 *
 * @property int $user_id
 * @property string $network
 * @property string $social_id
 * @method static \Illuminate\Database\Eloquent\Builder|SocialNetwork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialNetwork newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialNetwork query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialNetwork whereNetwork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialNetwork whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialNetwork whereUserId($value)
 * @mixin \Eloquent
 */
class SocialNetwork extends Model
{
    protected $table = 'user_networks';
    protected $fillable = ['network', 'social_id'];
    public $timestamps = false;
}
