<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/25/2018
 * Time: 4:23 PM
 */
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OwnerUser;
use App\Repository\RegistrationRepo;
use Illuminate\Http\Request;
use App\Repository\OwnerRepo;
use Auth;

class OwnerController extends Controller
{
    protected $ownerRepo;
    protected $registrationRepo;

    public function __construct(OwnerRepo $ownerRepo, RegistrationRepo $registrationRepo)
    {
        $this->ownerRepo = $ownerRepo;
        $this->registrationRepo = $registrationRepo;
    }

    //Get the location path in one object => country, city and district using nested relation
    public function location()
    {
        $result = $this->ownerRepo->getLocation();
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }

    //Options in creating location and fields
    public function playgroundSportFeatureOptions()
    {
        $result['playgrounds'] = $this->ownerRepo->getPlaygroundType();
        $result['sports'] = $this->ownerRepo->getSportType();
        $result['fieldSize'] = $this->ownerRepo->getFieldSize();
        $result['features'] = $this->ownerRepo->getFeatures();
        $result['bookingRequest'] = $this->ownerRepo->getBookingRequest();
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }

    //Add new location
    public function addLocation(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->ownerRepo->setReq($request);
            $result = $this->ownerRepo->storeLocation(Auth::user());
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                return response()->json(['code' => 100, 'message' => $result['validator']->errors(), 'item' => '']);
            } elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Location Added Successfully', 'item' => '']);
            }
        }
    }

    //list of owner locations
    public function getOwnerLocations()
    {
        $result = $this->ownerRepo->listLocations(Auth::user());
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }

    //Add new Field to certain location
    public function addField(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->ownerRepo->setReq($request);
            $result = $this->ownerRepo->storeField();
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                if ($result['validator'] == 'exceedFields')
                    return response()->json(['code' => 100, 'message' => "You reached the maximum fields to this location" ,'item' => '']);
                else
                    return response()->json(['code' => 100, 'message' => $result['validator']->errors() ,'item' => '']);
            }  elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Field Added Successfully','item' => '']);
            }
        }
    }

    //Upload field Images
    public function fieldImages(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->ownerRepo->setReq($request);
            $result = $this->ownerRepo->uploadFieldImages();
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                if ($result['validator'] == 'noImage')
                    return response()->json(['code' => 100, 'message' => 'You must upload at least one Image', 'item' => '']);
                else
                    return response()->json(['code' => 100, 'message' => $result['validator']->errors(), 'item' => '']);
            } elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Images Uploaded Successfully', 'item' => '']);
            }
        }
    }

    //Create Field Owner Users
    public function createOwnerUser(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->ownerRepo->setReq($request);
            $result = $this->registrationRepo->register();
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                 return response()->json(['code' => 100, 'message' => $result['validator']->errors() ,'item' => '']);
            }  elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'User Added Successfully','item' => '']);
            }
        }
    }

    //Get list of Owner Users
    public function ownerUsers()
    {
        $result = $this->ownerRepo->getOwnerUsers(Auth::user());
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }
}