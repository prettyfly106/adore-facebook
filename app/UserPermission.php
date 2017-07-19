<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    //
    protected $table = 'user_has_permission';
    //protected $primaryKey = ['idPermission','idUser'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idUser', 'idPermission'
    ];
}
