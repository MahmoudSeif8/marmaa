<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 10:49 AM
 */
namespace App\Models;

class Position extends Model
{
    public function category()
    {
        return $this->belongsTo(PositionCategory::class);
    }
}