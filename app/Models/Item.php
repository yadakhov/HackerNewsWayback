<?php

namespace App\Models;

class Item extends BaseModel
{
    protected $table = 'items';

    public $timestamps = true;

    protected $guarded = [];
}
