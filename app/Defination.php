<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Defination extends Model
{
    protected $fillable = ['khademyar_id', 'user_id', 'moarefi', 'moavenat', 'tozih', 'molahezat', 'sh_letter', 'date_letter'];

    public function khademyars()
    {
        return $this->belongsTo(Khademyar::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}