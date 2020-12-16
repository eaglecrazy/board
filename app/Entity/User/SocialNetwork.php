<?php

namespace App\Entity\User;

use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    protected $table = 'user_networks';
    protected $fillable = ['network', 'identity'];
//    public $timestamps = false;
}
