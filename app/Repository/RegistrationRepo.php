<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 11:28 AM
 */
namespace App\Repository;

use App\Models\OwnerUser;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\UserVerification;

//use App\Mail\ActivationCode;
//use App\Mail\ResetCode;

class RegistrationRepo
{
    private $request;
    protected $validator = null;
    private $result = array();
    public $userImage_folder = User::UPLOAD_FOLDER;

    public function setReq(Request $request)
    {
        $this->request = $request;
    }

    public function UserValidator()
    {
        $this->validator = Validator::make($this->request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        return $this->validator;
    }
    public function FieldOwnerValidator()
    {
        $this->validator = Validator::make($this->request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required',
            'password' => 'required|string|min:6',
        ]);
        return $this->validator;
    }

    public function login()
    {
        $field = 'email';
        if (filter_var($this->request->input('login'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }

        if (Auth::attempt([$field => $this->request->input('login'),
            'password' => $this->request->input('password') , 'isVerify' => 0])) {
            $this->result = ['message' => 'notVerify', 'item' => ''];
            return $this->result;
        }
        elseif (Auth::attempt([$field => $this->request->input('login'),
            'password' => $this->request->input('password') , 'isVerify' => 1])){
            $this->result = ['message' => 'success', 'item' => ''];
            return $this->result;
        }
        else {
            User::where($field, $this->request->input('login'))->first();
            $this->result = ['message' => 'errors', 'item' => ''];
            return $this->result;
        }
    }

    public function generate_code($length) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function register()
    {
        if ($this->request->actorId == 2)
            $this->UserValidator();
        elseif($this->request->actorId == 3 || $this->request->actorId == 4)
            $this->FieldOwnerValidator();

        if ($this->validator->fails()) {
            return $this->result = ['validator' => $this->validator, 'success' => "", 'errors' => ""];
        } else {
            DB::beginTransaction();
            try {
                if ($this->request->actorId == 3 || $this->request->actorId == 4){
                    $image_name = NULL;
                    if($this->request->hasFile('image')) {
                        $image_path = public_path($this->userImage_folder);
                        $file = $this->request->file('image');
                        if(!empty($image_path)){
                            $image_name = time().'.'.$file->getClientOriginalExtension();
                        }
                    }
                    $newUser = User::create([
                        'name' => $this->request->name,
                        'email' => $this->request->email,
                        'mobile' => $this->request->mobile,
                        'password' => bcrypt($this->request->password),
                        'image' => $image_name,
                        'actor_id' => $this->request->actorId,
                    ]);
                    if (isset($file))
                        $file->move($image_path, $image_name);

                    if ($this->request->actorId == 4)
                    {
                        OwnerUser::create([
                           'user_id' => $newUser->id,
                            'field_owner_id' => Auth::user()->id,
                        ]);
                    }
                }
                else{
                    $newUser = User::create([
                        'name' => $this->request->name,
                        'email' => $this->request->email,
                        'password' => bcrypt($this->request->password),
                        'actor_id' => $this->request->actorId,
                    ]);
                }

                $activateCode = $this->generate_code(6);
                UserVerification::create([
                    'user_id' => $newUser->id,
                    'verification_code' => $activateCode,
                ]);

                // \Mail::to($this->request->email)->send(new ActivationCode($activateCode));

                DB::commit();
                return $this->result = ['validator' => "", 'success' => 'success', 'errors' => "", 'user_id' => $newUser->id];
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        }
    }

    public function verify()
    {
        $result = UserVerification::where([['user_id', $this->request->user_id], ['verification_code', $this->request->verification_code]])->count();
        if ($result > 0) {
            DB::beginTransaction();
            try {
                $checkUser = User::where('id', $this->request->user_id)->first();
                if ($checkUser) {
                    $checkUser->update([
                        'isVerify' => '1',
                    ]);
                }
                DB::commit();
                return $this->result = ['validator' => "", 'success' => "success", 'errors' => ""];
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
            }
        } else {
            return $this->result = ['validator' => "Invalid Code", 'success' => "", 'errors' => ""];
        }
    }

    public function findOrCreateUserByProvider($user , $provider)
    {
        DB::beginTransaction();
        if($user->getEmail()) {
            $authUser = User::where('provider_id', $user->getId())->where('email', $user->getEmail())->first();
            $authByEmail = User::where('email', $user->getEmail())->first();
            if ($authUser) {
                if ($authUser->isVerify == 0) {
                    $this->result = ['validator' => "notVerify", 'success' => "", 'errors' => ""];
                } else {
                    $this->result = ['validator' => "", 'success' => $authUser, 'errors' => ""];
                }
                return $this->result;
            }
            elseif ($authByEmail)
            {
                $this->result = ['validator' => "", 'success' => $authUser, 'errors' => ""];
                return $this->result;
            }
            else
            {
                try {
                    $create['name'] = $user->getName();
                    $create['email'] = $user->getEmail();
                    $create['provider'] = $provider;
                    $create['provider_id'] = $user->getId();
                    $userModel = new User;
                    $createdUser = $userModel->addNewProviderUser($create);
                    $this->result = ['message' => "User created successfully", 'success' => $createdUser, 'errors' => ""];
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollback();
                    $this->result = ['validator' => "", 'success' => "", 'errors' => $exception];
                }
            }
        }
        else
        {
            $this->result = ['validator' => "No Email", 'success' => "", 'errors' => ""];
        }
        return $this->result;
    }
}