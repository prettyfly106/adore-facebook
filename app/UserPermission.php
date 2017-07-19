<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    //
    protected $table = 'user_has_permission';
    //protected $primaryKey = 'idPermission';
    protected $fillable = [
        'idUser', 'idPermission'
    ];
}
