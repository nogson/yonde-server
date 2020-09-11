<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagMap extends Model
{
    protected $guarded = [];

    public function tag() {
        return $this->belongsTo('App\Models\Tag');
    }
}
