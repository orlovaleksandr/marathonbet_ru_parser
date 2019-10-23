<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchContent extends Model
{
    protected $fillable = ['content', 'match_id'];
}
