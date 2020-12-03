<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SignInController extends Controller
{
     public function signIn(Request $request) {

        Log::debug($request);

        return $request;
     }
}
