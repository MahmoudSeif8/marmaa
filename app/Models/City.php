<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:14 PM
 */
namespace App\Models;

class City extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function district()
    {
        return $this->hasMany(District::class);
    }
}