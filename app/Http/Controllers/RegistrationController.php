<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
     public function register() {

         $this->validate(request(), [
             'name' => 'required',
             'email' => 'required|email',
             'password' => 'required'
         ]);

        $user = new User();
        $user->name = request('name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));

        $user->save();

        dd($user);

        return response()->json(['try' => 'good try!'], 200);
     }
}
