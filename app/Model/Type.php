<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class type extends Model
{
    protected $table = 'type';
    protected $primaryKey = 'type_id';
    public $timestamps = false;
    protected $guarded = [];
}
