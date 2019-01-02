<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:21 PM
 */
namespace App\Repository;

use App\Models\Country;
use App\Models\Feature;
use App\Models\Field;
use App\Models\FieldImage;
use App\Models\FieldLocation;
use App\Models\FieldSize;
use App\Models\OwnerUser;
use App\Models\PlaygroundType;
use App\Models\SportType;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class OwnerRepo
{
    private $request;
    protected $validator = null;
    private $result = array();

    public $userImage_folder = User::UPLOAD_FOLDER;
    public $fieldImage_folder = FieldImage::UPLOAD_FOLDER;

    public function setReq(Request $request)
    {
        $this->request = $request;
    }

    public function LocationValidator()
    {
        $this->validator = Validator::make($this->request->all(), [
            'name' => 'required|string|max:255',
            'fieldNum' => 'required|integer|min:1',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'country_id' =>  'required|integer',
            'city_id' =>  'required|integer',
            'district_id' =>  'required|integer',
        ]);
        return $this->validator;
    }
    public function FieldValidator()
    {
        $this->validator = Validator::make($this->request->all(), [
            'name' => 'required|string|max:255',
            'field_location_id' => 'required|integer',
            'sport_type_id' => 'required|integer',
            'playground_type_id' => 'required|integer',
        ]);
        return $this->validator;
    }
    public function ImageValidator()
    {
        $this->validator = Validator::make($this->request->all(), [
            'images.*' => 'required|image|mimes:jpeg,jpg,png,JPG',
        ]);
        return $this->validator;
    }

    public function getLocation()
    {
        $location['country'] = Country::with('city.district')->get();
        return $location;
    }

    public function getPlaygroundType()
    {
        $playgrounds = PlaygroundType::all();
        return $playgrounds;
    }

    public function getSportType()
    {
        $sports = SportType::all();
        return $sports;
    }

    public function getFeatures()
    {
        $features = Feature::all();
        return $features;
    }

    public function getFieldSize()
    {
        $fieldSize = FieldSize::all();
        return $fieldSize;
    }

    public function storeLocation($user)
    {
        $this->LocationValidator();
        if ($this->validator->fails()) {
            return $this->result = ['validator' => $this->validator, 'success' => "", 'errors' => ""];
        } else {
            DB::beginTransaction();
            try {
                FieldLocation::create([
                    'name' => $this->request->name,
                    'fieldNum'=> $this->request->fieldNum,
                    'user_id' => $user->id,
                    'longitude' => $this->request->longitude,
                    'latitude'=> $this->request->latitude,
                    'country_id' => $this->request->country_id,
                    'city_id' => $this->request->city_id,
                    'district_id' => $this->request->district_id,
                ]);
                DB::commit();
                return $this->result = ['validator' => "", 'success' => 'success', 'errors' => ""];
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        }
    }

    public function listLocations($user)
    {
        $locations = FieldLocation::where('user_id',$user->id)->select('id','name')->get();
        return $locations;
    }

    public function storeField()
    {
        $this->FieldValidator();
        if ($this->validator->fails()) {
            return $this->result = ['validator' => $this->validator, 'success' => "", 'errors' => ""];
        } else {
            DB::beginTransaction();
            try {
                $fields = FieldLocation::with('field')->where('id',$this->request->field_location_id)->first();
                if (count($fields->field) < $fields->fieldNum){
                    //license
                    $fieldInformation = [
                        'name' => $this->request->name,
                        'field_location_id' => $this->request->field_location_id,
                        'sport_type_id' => $this->request->sport_type_id,
                        'playground_type_id' => $this->request->playground_type_id,
                        'forWomen' => $this->request->forWomen,
                    ];
                    if ($this->request->sport_type_id == 1){
                        $fieldSize = [
                            'field_size_id' => $this->request->field_size_id,
                        ];
                        $data = array_merge($fieldInformation,$fieldSize);
                        Field::create($data);
                    }
                    else{
                        Field::create($fieldInformation);
                    }

                    DB::commit();
                    return $this->result = ['validator' => "", 'success' => 'success', 'errors' => ""];
                }
                else
                    return $this->result = ['validator' => "exceedFields", 'success' => "", 'errors' => ""];
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        }
    }

    public function uploadFieldImages()
    {
        $this->ImageValidator();
        if ($this->validator->fails()) {
            return $this->result = ['validator' => $this->validator, 'success' => "", 'errors' => ""];
        } else {
            DB::beginTransaction();
            try {
                $check = Field::find($this->request->field_id);
                if ($check){
                    if (isset($this->request->images)){
                        $images = $this->request->file('images');
                        $count = 1;
                        foreach ($images as $image){
                            $image_path = public_path($this->fieldImage_folder);
                            if(!empty($image_path)){
                                $image_name =  $this->request->field_id . '_' . $count . time().'.'. $image->getClientOriginalExtension();
                                FieldImage::create([
                                    'field_id' => $this->request->field_id,
                                    'image' => $image_name,
                                ]);
                                $image->move($image_path, $image_name);
                            }
                            $count++;
                        }
                        DB::commit();
                        return $this->result = ['validator' => "", 'success' => 'success', 'errors' => ""];
                    }
                    else
                    {
                        return $this->result = ['validator' => "noImage", 'success' => "", 'errors' => ""];
                    }
                }
                else
                    return $this->result = ['validator' => "", 'success' => "", 'errors' => "Something went wrong"];

            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        }
    }
}