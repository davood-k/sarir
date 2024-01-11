<?php

namespace App;

use App\Khadem;
use App\Comision;
use Illuminate\Database\Eloquent\Model;

class Azmoon extends Model
{
    protected $fillable = ['khadem_id', 'nomrehAzmoonsr', 'comisionsr'];

    public function khadems()
    {
        return $this->belongsTo(Khadem::class);
    }
}
