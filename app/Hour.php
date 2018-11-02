<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    protected $filable = [
        'hour_first', 'hour_second', 'hour_third', 'hour_fourth', 'hour_fifth', 'hour_sixth', 
        'hour_seventh', 'hour_eighth', 'hour_ninth', 'hour_tenth', 'hour_eleventh', 'hour_twelfth'
    ];
}
