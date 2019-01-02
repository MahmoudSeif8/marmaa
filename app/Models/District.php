<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:14 PM
 */
namespace App\Models;

class District extends Model
{
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}