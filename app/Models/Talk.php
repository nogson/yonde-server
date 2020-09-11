<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
    protected $guarded = [];

    public function tags() {
        return $this->hasMany('App\Models\TagMap');
    }
}
