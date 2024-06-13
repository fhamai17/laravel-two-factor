<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use PragmaRX\Google2FA\Google2FA;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('language');
    }

    public function index(Request $request)
    {
        return response()->json(['success'=>true, 'home' =>  'test 2fa' ]);
    }
}
