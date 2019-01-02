<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 5:00 PM
 */
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repository\UserRepo;
use Auth;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    //List Consumer Types
    public function consumerTypes()
    {
        $result['types'] = $this->userRepo->getConsumerType();
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }

    //Choose consumer type
    public function chooseType(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->userRepo->setReq($request);
            $result = $this->userRepo->addUserType(Auth::user());
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                return response()->json(['code' => 100, 'message' => $result['validator'] ,'item' => '']);
            } elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Type Added Successfully']);
            }
        }
    }

    //List positions per category for player
    public function categoryPositions()
    {
        $result['positions'] = $this->userRepo->getPositionOfCategory();
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }

    //List Payment and Costing options
    public function PaymentCostingOptions()
    {
        $result['PaymentOptions'] = $this->userRepo->getPaymentOption();
        $result['CostingOptions'] = $this->userRepo->getCostingOption();
        $result['BankCardTypes'] = $this->userRepo->getBankCardType();
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }

    //Show User Profile data
    public function userProfile()
    {
        $result = $this->userRepo->showUserProfile(Auth::user());
        if (!empty($result)) {
            return response()->json(['code' => 200, 'message' => $result, 'item' => '']);
        } else {
            return response()->json(['code' => 100, 'message' => 'Error', 'item' => '']);
        }
    }

    //Update User profile
    public function updateProfile(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->userRepo->setReq($request);
            $result = $this->userRepo->updateUserProfile(Auth::user());
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                return response()->json(['code' => 100, 'message' => $result['validator']->errors() ,'item' => '']);
            } elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Profile Updated Successfully', 'item' => '']);
            }
        }
    }

    //Broker/Referee Add new Paper
    public function addDocument(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->userRepo->setReq($request);
            $result = $this->userRepo->addNewDocument(Auth::user());
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                return response()->json(['code' => 100, 'message' => $result['validator'] ,'item' => '']);
            } elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Document Added Successfully', 'item' => '']);
            }
        }
    }

    //Broker/Referee Update existing paper
    public function updateDocument(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->userRepo->setReq($request);
            $result = $this->userRepo->updateExistingDocument(Auth::user());
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                return response()->json(['code' => 100, 'message' => $result['validator'] ,'item' => '']);
            } elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Document Updated Successfully', 'item' => '']);
            }
        }
    }

    //Delete Broker/Referee document from his profile
    public function deleteDocument($documentId)
    {
        $result = $this->userRepo->deleteUserDocument(Auth::user(),$documentId);
        if ($result['errors']) {
            return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
        } elseif ($result['validator']) {
            return response()->json(['code' => 100, 'message' => $result['validator'] ,'item' => '']);
        } elseif ($result['success']) {
            return response()->json(['code' => 200, 'message' => 'Document Deleted Successfully', 'item' => '']);
        }
    }

    //Choose Payment Method
    public function choosePaymentOption(Request $request)
    {
        if (!$request->all()) {
            return response()->json(['code' => 100, 'message' => "No Parameters Found", 'item' => '']);
        } else {
            $this->userRepo->setReq($request);
            $result = $this->userRepo->storePaymentOption(Auth::user());
            if ($result['errors']) {
                return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
            } elseif ($result['validator']) {
                if ($result['validator'] == 'optionFound')
                    return response()->json(['code' => 100, 'message' => "You choose an option before" ,'item' => '']);
                else
                    return response()->json(['code' => 100, 'message' => $result['validator']->errors() ,'item' => '']);
            } elseif ($result['success']) {
                return response()->json(['code' => 200, 'message' => 'Data Added Successfully', 'item' => '']);
            }
        }
    }
}