<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FALaravel\Google2FA;

class LoginController extends Controller
{
    public function __construct()
    {
        // $this->middleware('language');
    }
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        //
        try {
            Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
                'one_time_password' => 'required',
                'google2fa_secret' => 'required'
            ], [])->validate();

            //$google2fa = app('pragmarx.google2fa');
            $google2fa = new Google2FA($request);
            // $valid = $google2fa->verifyKey($request->google2fa_secret, $request->one_time_password);
            // if ($valid) {
            //     return response()->json(['success' => true, 'message' => 'Valid otp']);
            // } else {

            //     return response()->json(['success' => false, 'message' => 'Invalid otp']);
            // }
            $user = User::where('email', $request->email)->first();
            // $google2fa = new Google2FA($request);

            if (!$google2fa->verifyKey($user->google2fa_secret, $request->one_time_password, 2)) {
                throw ValidationException::withMessages([
                    'one_time_password' => [__('The one time password is invalid.')],
                ]);
            }

            //ของคนอื่น
            //$google2fa->login();

            //ใส่ session เอง
            Session::put('auth_passed',true);
            $session = Session::get('auth_passed');
            return response()->json(['success' => true, 'message' => 'Log in success!', 'session' => $session]);
        } catch (ValidationException $e) {
            $ret["success"] = false;
            $ret["message"] = $e->errors();
            return response()->json($ret);
        } catch (Exception $e) {
            $ret["success"] = false;
            $ret["message"] = $e->getMessage();
            return response()->json($ret);
        }
    }

    public function logout(Request $request)
    {
        //
        try {
            // ของคนอื่น
            // $google2fa = new Google2FA($request);
            // $google2fa->logout();

            //ลบ session เอง
            Session::forget('auth_passed');
            if(!Session::has('auth_passed'))
             {
                return response()->json(['success' => true, 'message' => 'Log out success!']);
             }
        } catch (ValidationException $e) {
            $ret["success"] = false;
            $ret["message"] = $e->errors();
            return response()->json($ret);
        } catch (Exception $e) {
            $ret["success"] = false;
            $ret["message"] = $e->getMessage();
            return response()->json($ret);
        }
    }
}
