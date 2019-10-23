<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = ['name', 'url'];


    public function matchContent()
    {
        return $this->hasOne(MatchContent::class);
    }
}
