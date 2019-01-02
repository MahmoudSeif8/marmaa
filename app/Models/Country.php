<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:13 PM
 */
namespace App\Models;

class Country extends Model
{
    public function city()
    {
        return $this->hasMany(City::class);
    }
}