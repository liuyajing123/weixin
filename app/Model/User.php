<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user_token';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $guarded = [];
}
