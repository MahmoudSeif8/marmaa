<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:15 PM
 */
namespace App\Models;

class Feature extends Model
{
    use GetImageTrait;
    const UPLOAD_FOLDER = '/features/';

    protected $appends = ['image_path'];
}