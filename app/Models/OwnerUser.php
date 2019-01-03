<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:18 PM
 */
namespace App\Models;

class OwnerUser extends Model
{
    public function info()
    {
        return $this->belongsTo(User::class);
    }
}