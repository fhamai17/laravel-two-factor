<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use DateTime;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


use PragmaRX\Google2FALaravel\Google2FA;

class RegisterController extends Controller
{

    public function store(Request $request)
    {
        //
        try {
            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [])->validate();

            $google2fa = app('pragmarx.google2fa');
            $tfa_secretkey = $google2fa->generateSecretKey(32);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'google2fa_secret' => $tfa_secretkey
            ]);

            $qrcode = $google2fa->getQRCodeInline(
                'Backend-POS',
                $user->email,
                $user->google2fa_secret
            );

            return $qrcode;
            return response()->json(['success' => false, 'user' => $user, 'url' => $qrcode]);
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

    // public function store(Request $request)
    // {
    //     //return 'test';
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     // $google2fa = app('pragmarx.google2fa');

    //     // $user = User::create([
    //     //     'name' => $request->name,
    //     //     'email' => $request->email,
    //     //     'password' => Hash::make($request->password),
    //     //     'google2fa_secret' => $google2fa->generateSecretKey()
    //     // ]);


    //     // $google2fa->setAllowInsecureCallToGoogleApis(true);

    //     // $google2fa_url = $google2fa->getQRCodeGoogleUrl(
    //     //     'GenTech',
    //     //     $user->email,
    //     //     $user->google2fa_secret
    //     // );

    //     //return response()->json(['success' => false, 'user' => $user, 'url' => $google2fa_url]);
    // }
}
