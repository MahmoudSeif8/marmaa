<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:14 PM
 */
namespace App\Models;

class OwnerLocation extends Model
{
    public function field()
    {
        return $this->hasMany(Field::class);
    }
}