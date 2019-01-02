<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:18 PM
 */
namespace App\Models;

class Field extends Model
{
    public function location()
    {
        return $this->belongsTo(FieldLocation::class);
    }

    public function images()
    {
        return $this->hasMany(FieldImage::class);
    }
}