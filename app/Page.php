<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    protected $table = 'facebook_pages';
    //public $timestamps = false;
    //protected $primaryKey = ['idPermission','idUser'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'user_id', 'approve_date','unwatch_date','status'
    ];
}
