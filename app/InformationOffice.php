<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InformationOffice extends Model
{
    protected $fillable = ['offices', 'personsRelation', 'numbers', 'mobiles', 'address', 'post', 'timeServices', 'description'];
}
