<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }

}
