<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 10:50 AM
 */
namespace App\Models;

class UserDocument extends Model
{
    use GetImageTrait;
    const UPLOAD_FOLDER = '/uploads/userDocuments/';

    protected $appends = ['image_path'];

    public function details()
    {
        return $this->belongsTo(User::class);
    }

}