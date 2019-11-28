<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KaoGoods extends Model
{
    protected $table = 'kao_goods';
    protected $primaryKey = 'g_id';
    public $timestamps = false;
    protected $guarded = [];
}
