<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 10:51 AM
 */
namespace App\Models;

class UserInformation extends Model
{
    public function info()
    {
        return $this->belongsTo(User::class);
    }
}