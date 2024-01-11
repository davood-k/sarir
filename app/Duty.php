<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Duty extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'title', 'date', 'numbers', 'span', 'expires', 'descriptions', 'importantrange'];
}
