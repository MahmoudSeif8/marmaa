<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:18 PM
 */
namespace App\Models;

class FieldImage extends Model
{
    use GetImageTrait;
    const UPLOAD_FOLDER = '/uploads/fieldImages/';

    protected $appends = ['image_path'];

}