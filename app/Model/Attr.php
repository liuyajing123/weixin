<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class attr extends Model
{
    protected $table = 'attr';
    protected $primaryKey = 'attr_id';
    public $timestamps = false;
    protected $guarded = [];
}
