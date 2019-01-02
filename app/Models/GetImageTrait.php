<?php
namespace App\Models;

/**
 * Class GetImageTrait
 * @package Http\Model
 */
trait GetImageTrait
{

    /**
     * @param $value
     * @return string
     */

    public function getImagePathAttribute()
    {
        if (!$this->attributes['image']) {
            return asset('/assets/images/default-Image.jpg');
        }
        $main_upload_folder = config('divey.main_upload_folder');
        $class =__CLASS__;
        $user_folder = $class::UPLOAD_FOLDER;
        $image_path = $main_upload_folder.$user_folder.$this->attributes['image'];

        return asset($image_path);
    }
    /**
     * @param $value
     * @return string
     */

    public function getPhotoPathAttribute()
    {
        if (!$this->attributes['image']) {
            return asset('/assets/images/no-image.png');
        }elseif ($this->attributes['provider']){
            return $this->attributes['image'];
        }else {
            $main_upload_folder = config('divey.main_upload_folder');
            $class = __CLASS__;
            $user_folder = $class::UPLOAD_FOLDER;
            $image_path = $main_upload_folder . $user_folder . $this->attributes['image'];

            return asset($image_path);
        }
    }
    /**
     * @param $value
     * @return string
     */

    public function getVideoPathAttribute()
    {
        $main_upload_folder = config('divey.main_upload_folder');
        $class =__CLASS__;
        $video_folder = $class::UPLOAD_FOLDER;
        $video_path = $main_upload_folder.$video_folder.$this->attributes['video'];
        return asset($video_path);
    }
    /**
     * @param $value
     * @return string
     */

    public function getIconPathAttribute()
    {
        if (!$this->attributes['icon']) {
            //return asset('/assets/images/no-image.png');
        }
        $main_upload_folder = config('divey.main_upload_folder');
        $class =__CLASS__;
        $user_folder = $class::UPLOAD_FOLDER;
        $image_path = $main_upload_folder.$user_folder.$this->attributes['icon'];
        return asset($image_path);
    }
}