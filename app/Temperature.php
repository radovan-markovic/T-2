<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    protected $filable = [
        'user_id', 'city_code', 'temp_first', 'temp_second', 'temp_third', 'temp_fourth', 'temp_fifth', 'temp_sixth', 
        'temp_seventh', 'temp_eighth', 'temp_ninth', 'temp_tenth', 'temp_eleventh', 'temp_twelfth', 'hour_id'
    ];

    public function hour()
    {
        return $this->hasOne('App\Temperature');
    }
}

