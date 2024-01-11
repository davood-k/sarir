<?php

namespace App;

use App\Azmoon;
use App\Khadem;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $fillable = ['khadem_id ', 'ShHerasatsr', 'TdHerasatsr', 'ShToliatsr', 'TdToliatsr', 'SiMKhodamsr', 'SiMSarmayehsr', 'SiMAalesr', 'SiToliatsr', 'ShHokmsr'];

    public function khadems()
    {
        return $this->belongsTo(Khadem::class);
    }

    public function azmoons()
    {
        return $this->belongsTo(Azmoon::class);
    }
}
