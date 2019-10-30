<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'catrgory_id';
    public $timestamps = false;
    protected $guarded = [];
}
