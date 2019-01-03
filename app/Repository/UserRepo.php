<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 5:05 PM
 */
namespace App\Repository;

use App\Models\BankCardType;
use App\Models\CostingOption;
use App\Models\PaymentOption;
use App\Models\PlayerPosition;
use App\Models\Position;
use App\Models\PositionCategory;
use App\Models\UserBankAccount;
use App\Models\UserBankCard;
use App\Models\UserInformation;
use App\Models\UserDocument;
use App\Models\UserPaymentOption;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\ConsumerType;
use App\Models\UserConsumerType;

class UserRepo
{
    private $request;
    protected $validator = null;
    private $result = array();
    public $userImage_folder = User::UPLOAD_FOLDER;
    public $userDocument_folder = UserDocument::UPLOAD_FOLDER;

    public function setReq(Request $request)
    {
        $this->request = $request;
    }

    public function BankAccountValidator()
    {
        $this->validator = Validator::make($this->request->all(), [
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|alpha_num',
            'IBAN' => 'required|integer',
        ]);
        return $this->validator;
    }
    public function BankCardValidator()
    {
        $this->validator = Validator::make($this->request->all(), [
            'card_name' => 'required|string|max:255',
            'card_number' => 'required|integer',
            'CVV' => 'required|integer|digits:3',
            'expires' => 'required|string|max:255',
            'bank_card_type_id' => 'required|integer',
        ]);
        return $this->validator;
    }


    public function getConsumerType()
    {
        $types = ConsumerType::all();
        return $types;
    }

    public function getPositionOfCategory()
    {
        $category = PositionCategory::with('position')->get();
        return $category;
    }

    public function getPaymentOption()
    {
        $paymentOptions = PaymentOption::all();
        return $paymentOptions;
    }

    public function getCostingOption()
    {
        $costingOptions = CostingOption::all();
        return $costingOptions;
    }

    public function getBankCardType()
    {
        $cardTypes = BankCardType::all();
        return $cardTypes;
    }

    public function addUserType($user)
    {
        $result = UserConsumerType::where([['user_id', $user->id], ['consumer_type_id', $this->request->type]])->count();
        if ($result > 0) {
            return $this->result = ['validator' => "User Already choose this type before", 'success' => "", 'errors' => ""];
        } else {
            DB::beginTransaction();
            try {
                UserConsumerType::create([
                    'user_id' => $user->id,
                    'consumer_type_id' => $this->request->type,
                ]);
                DB::commit();
                return $this->result = ['validator' => "", 'success' => "success", 'errors' => ""];
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        }
    }

    public function showUserProfile($user)
    {
        $data['user'] = User::with(['profile','document','position'])->find($user->id);
        /*$data = User::with('profile')->where('id',$user->id)
            ->select('name','mobile','email','nationality','image','bio')->first();*/
        return $data;
    }

    public function updateUserProfile($user)
    {
        $check = User::find($user->id);
        if ($check){
            DB::beginTransaction();
            try {
                $rows = $this->request->all();
                $check->name = $rows['name'];
                $check->nationality = $rows['nationality'];
                $check->bio = $rows['bio'];
                $check->mobile = $rows['mobile'];
                /*$check->email = $rows['[email'];*/
                if($this->request->hasFile('image')) {
                    $file = $this->request->file('image');
                    $image_path = public_path($this->userImage_folder);
                    if(!empty($image_path)){
                        $image_name =  time().'.'.$file->getClientOriginalExtension();
                        if (!empty($check->image)){
                            $oldImage = public_path($this->userImage_folder) . $check->image;
                            //Search for the file in the mentioned directory
                            $checkImage = glob($oldImage);
                            if ($checkImage)
                                unlink($oldImage);
                        }
                        $check->image = $image_name;
                        $check->save();
                        $file->move($image_path, $image_name);
                    }
                }
                else {
                    $check->save();
                }

                $checkInfo = UserInformation::where('user_id', $user->id)->count();
                switch ($this->request->profileType) {
                    case 1:
                        $userInformation = [
                            'foot' => $rows['foot'],
                            'age' => $rows['age'],
                            'height' => $rows['height'],
                            'weight' => $rows['weight'],
                        ];
                        if (isset($rows['position'])){
                            if (count($rows['position']) > 0){
                                $checkPositions = PlayerPosition::where('user_id',$user->id)->count();
                                if ($checkPositions > 0)
                                    PlayerPosition::where('user_id',$user->id)->delete();
                                foreach ($rows['position'] as $position){
                                    PlayerPosition::create([
                                        'user_id' => $user->id,
                                        'position_id' => $position,
                                    ]);
                                }
                            }
                        }
                        break;
                    case 2:
                        $userInformation = [
                            'height' => $rows['height'],
                            'weight' => $rows['weight'],
                        ];
                        break;
                    case 3:
                        $userInformation = [
                            'camera_model' => $rows['cameraModel'],
                            'camera_brand' => $rows['cameraBrand'],
                            'holder_model' => $rows['holderModel'],
                            'holder_brand' => $rows['holderBrand'],
                            'lens_model' => $rows['lensModel'],
                            'lens_brand' => $rows['lensBrand'],
                            'zoom_model' => $rows['zoomModel'],
                            'zoom_brand' => $rows['zoomBrand'],
                        ];
                        break;
                    case 4:
                        $userInformation = [
                            'mic_model' => $rows['micModel'],
                            'mic_brand' => $rows['micBrand'],
                        ];
                        break;
                    default:
                        return $this->result = ['validator' => "", 'success' => "", 'errors' => "Something Went Wrong"];
                }

                if ($checkInfo > 0)
                    UserInformation::where('user_id', $user->id)->update($userInformation);
                else{
                    $userId = [
                        'user_id' => $user->id,
                    ];
                    $data = array_merge($userInformation,$userId);
                    UserInformation::create($data);
                }
                DB::commit();
                return $this->result = ['validator' => "", 'success' => "success", 'errors' => ""];
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        }
        else{
            return $this->result = ['validator' => "User Not Found", 'success' => "", 'errors' => ""];
        }
    }

    public function addNewDocument($user)
    {
        $check = User::find($user->id);
        if ($check) {
            DB::beginTransaction();
            try {
                $hasPermission = UserConsumerType::where('user_id' , $user->id)
                    ->where(function($query) {
                        $query->where('consumer_type_id', 2)
                            ->orWhere('consumer_type_id', 5);
                    })
                    ->count();
                if ($hasPermission > 0){
                    switch ($this->request->type) {
                        case 1:
                            $documentType = 'e';
                            break;
                        case 2:
                            $documentType = 'c';
                            break;
                        default:
                            return $this->result = ['validator' => "", 'success' => "", 'errors' => "Something Went Wrong"];
                    }
                    $newPaper = [
                        'user_id' => $user->id,
                        'name' => $this->request->name,
                        'from' => $this->request->from,
                        'to' => $this->request->to,
                        'document_type_id' => $this->request->type,
                    ];
                    if($this->request->hasFile('image')) {
                        $image_path = public_path($this->userDocument_folder);
                        $file = $this->request->file('image');
                        if(!empty($image_path)){
                            $image_name =  $documentType . time().'.'.$file->getClientOriginalExtension();
                            $paperImage = [
                                'image' => $image_name,
                            ];
                            $paper = array_merge($newPaper,$paperImage);
                            UserDocument::create($paper);
                            $file->move($image_path, $image_name);
                        }
                    }
                    else {
                        UserDocument::create($newPaper);
                    }
                    DB::commit();
                    return $this->result = ['validator' => "", 'success' => "success", 'errors' => ""];
                }
                else{
                    return $this->result = ['validator' => "Something went wrong", 'success' => "", 'errors' => ""];
                }
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        } else {
            return $this->result = ['validator' => "User Not Found", 'success' => "", 'errors' => ""];
        }
    }

    public function updateExistingDocument($user)
    {
        $check = User::find($user->id);
        if ($check) {
            DB::beginTransaction();
            try {
                $hasPermission = UserConsumerType::where('user_id' , $user->id)
                    ->where(function($query) {
                        $query->where('consumer_type_id', 2)
                            ->orWhere('consumer_type_id', 5);
                    })
                    ->count();
                if ($hasPermission > 0){
                    $editPaper = UserDocument::where([ ['id' , $this->request->id],['user_id',$user->id] ])->first();
                    if ($editPaper){
                        switch ($editPaper->document_type_id) {
                            case 1:
                                $documentType = 'e';
                                break;
                            case 2:
                                $documentType = 'c';
                                break;
                            default:
                                return $this->result = ['validator' => "", 'success' => "", 'errors' => "Something Went Wrong"];
                        }
                        $editPaper->name = $this->request->name;
                        $editPaper->from = $this->request->from;
                        $editPaper->to = $this->request->to;
                        if($this->request->hasFile('image')) {
                            $image_path = public_path($this->userDocument_folder);
                            $file = $this->request->file('image');
                            if(!empty($image_path)){
                                $image_name =  $documentType . time().'.'.$file->getClientOriginalExtension();
                                if (!empty($editPaper->image)){
                                    $oldImage = $image_path . $editPaper->image;
                                    //Search for the file in the mentioned directory
                                    $checkImage = glob($oldImage);
                                    if ($checkImage)
                                        unlink($oldImage);
                                }
                                $editPaper->image = $image_name;
                                $editPaper->save();
                                $file->move($image_path, $image_name);
                            }
                        }
                        else {
                            $editPaper->save();
                        }
                        DB::commit();
                        return $this->result = ['validator' => "", 'success' => "success", 'errors' => ""];
                    }
                    else
                        return $this->result = ['validator' => "No Document Found", 'success' => "", 'errors' => ""];
                }
                else{
                    return $this->result = ['validator' => "Something went wrong", 'success' => "", 'errors' => ""];
                }
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        } else {
            return $this->result = ['validator' => "User Not Found", 'success' => "", 'errors' => ""];
        }
    }

    public function deleteUserDocument($user,$id)
    {
        $check = User::find($user->id);
        if ($check) {
            DB::beginTransaction();
            try {
                $hasPermission = UserConsumerType::where('user_id' , $user->id)
                    ->where(function($query) {
                        $query->where('consumer_type_id', 2)
                            ->orWhere('consumer_type_id', 5);
                    })
                    ->count();
                if ($hasPermission > 0){
                    $document = UserDocument::where([ ['id' , $id],['user_id',$user->id] ])->first();
                    if ($document){
                        if(!empty($document->image)){
                            $image_path = public_path($this->userDocument_folder);
                            $image = $image_path . $document->image;
                            //Search for the file in the mentioned directory
                            $checkImage = glob($image);
                            if ($checkImage){
                                unlink($image);
                            }
                        }
                        $document->delete();
                        DB::commit();
                        return $this->result = ['validator' => "", 'success' => "success", 'errors' => ""];
                    }
                    else
                        return $this->result = ['validator' => "No Document Found", 'success' => "", 'errors' => ""];
                }
                else{
                    return $this->result = ['validator' => "Something went wrong", 'success' => "", 'errors' => ""];
                }
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        } else {
            return $this->result = ['validator' => "User Not Found", 'success' => "", 'errors' => ""];
        }
    }

    public function storePaymentOption($user)
    {
        switch ($this->request->payment_option_id) {
            case 1:
                $this->BankAccountValidator();
                break;
            case 2:
                $this->BankCardValidator();
                break;
            default:
                return $this->result = ['validator' => "", 'success' => "", 'errors' => "Something Went Wrong"];
        }
        if ($this->validator->fails()) {
            return $this->result = ['validator' => $this->validator, 'success' => "", 'errors' => ""];
        } else {
            DB::beginTransaction();
            try {
                $check = UserPaymentOption::where('user_id',$user->id)->first();
                if ($check){
                    return $this->result = ['validator' => "optionFound", 'success' => "", 'errors' => ""];
                }
                else{
                    $storePayment = UserPaymentOption::create([
                        'user_id' => $user->id,
                        'payment_option_id' => $this->request->payment_option_id,
                    ]);

                    switch ($this->request->payment_option_id) {
                        case 1:
                            UserBankAccount::create([
                                'user_id' => $user->id,
                                'bank_name' => $this->request->bank_name,
                                'account_number' => $this->request->account_number,
                                'IBAN' => $this->request->IBAN,
                                'user_payment_option_id' => $storePayment->id,
                            ]);
                            break;
                        case 2:
                            UserBankCard::create([
                                'user_id' => $user->id,
                                'card_name' => $this->request->card_name,
                                'card_number' => $this->request->card_number,
                                'CVV' => $this->request->CVV,
                                'expires' => $this->request->expires,
                                'bank_card_type_id' => $this->request->bank_card_type_id,
                                'user_payment_option_id' => $storePayment->id,
                            ]);
                            break;
                        default:
                            return $this->result = ['validator' => "", 'success' => "", 'errors' => "Something Went Wrong"];
                    }
                    DB::commit();
                    return $this->result = ['validator' => "", 'success' => 'success', 'errors' => ""];
                }
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        }
    }
}