<?php
/**
 * Created by PhpStorm.
 * User: msaif
 * Date: 12/13/2018
 * Time: 11:29 AM
 */
namespace App\Http\Controllers\Api;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use App\Repository\RegistrationRepo;
use Illuminate\Http\Request;
use Auth;

class AuthController extends AccessTokenController
{
    //Generate User Access Token to login to the application
    public function isUserToken(ServerRequestInterface $request, RegistrationRepo $registrationRepo)
    {
        $httpRequest = request();
        $httpRequest->request->add([
            'login' => $httpRequest->username,
            'grant_type' => 'password',
            'client_id' => 4,
            'client_secret' => 'SbXb9KCAnV8srWtaRRKaVIMyIjmTFRw7hdmzQGpg',
        ]);
        if (!$httpRequest->all()) {
            return response()->json(['code' => 100, 'message' => 'No Parameters Found', 'item' => '']);
        } else {
            $registrationRepo->setReq($httpRequest);
            $result = $registrationRepo->login();
            if ($result['message'] == 'success') {
                $user = Auth::user();
                return response()->json(['code' => 200, 'message' => $user->createToken('Marmaa')->accessToken, 'item' => '']);
                //return $this->issueToken($request);
            }
            elseif ($result['message'] == 'notVerify'){
                return response()->json(['code' => 100, 'message' => 'User Not Verified', 'item' => '']);
            }
            else {
                return response()->json(['code' => 100, 'message' => 'Invalid Email or Password', 'item' => '']);
            }
        }
    }

    //Create new consumer User
    public function newUser(Request $request, RegistrationRepo $registrationRepo)
    {
        $registrationRepo->setReq($request);
        $result = $registrationRepo->register();

        if ($result['errors']) {
            return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
        } elseif ($result['validator']) {
            return response()->json(['code' => 100, 'message' => $result['validator']->errors() ,'item' => '']);
        } elseif ($result['success']) {
            $message[0] = 'User Created Successfully';
            $success['success'] = $message;
            return response()->json(['code' => 200, 'message' => $success , 'item' => $result['user_id']]);
        }
    }

    //Verify User Account
    public function VerifyUser(Request $request, RegistrationRepo $registrationRepo)
    {
        $registrationRepo->setReq($request);
        $result = $registrationRepo->verify();
        if ($result['errors']) {
            return response()->json(['code' => 500, 'message' => $result['errors'], 'item' => '']);
        }elseif($result['validator']){
            return response()->json(['code' => 100, 'message' => $result['validator'], 'item' => '']);
        }
        elseif ($result['success']) {
            return response()->json(['code' => 200, 'message' => 'User Verified Successfully', 'item' => '']);
        }
    }

}