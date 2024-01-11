<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Khademyar extends Model
{
    protected $fillable = ['codemelli', 'fname', 'lname', 'gender'];

    public function definations()
    {
        return $this->hasMany(Defination::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }


}