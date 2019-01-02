<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 10:49 AM
 */
namespace App\Models;

class PositionCategory extends Model
{
    public function position()
    {
        return $this->hasMany(Position::class);
    }
}