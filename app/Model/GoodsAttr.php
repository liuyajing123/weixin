<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class goodsAttr extends Model
{
    protected $table = 'goods_attr';
    protected $primaryKey = 'goods_attr_id';
    public $timestamps = false;
    protected $guarded = [];
}
