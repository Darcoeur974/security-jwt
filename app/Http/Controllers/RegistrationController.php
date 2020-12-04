<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /**
     * @param $request
     * @return Application|RedirectResponse|Redirector
     */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ],[
            'required' => 'Le champs :attribute est requis.',
            'email' => 'Votre E-mail n\'est pas au bon format',
            'confirmed' => 'Vos mots de passe ne correspondent pas.',
            'min' => 'Le mot de passe doit faire un minimun de 6 characteres.',
        ]);

        if ($validator->fails()) {
            return redirect('register')
                ->withErrors($validator)
                ->withInput();
        }

        $validData = $validator->validate();

        $user= DB::table('users')
            ->where('email', $validData['email'])->get();

        if ($user->count() !== 0) {
            if ($user[0]->email === $validData['email']) {
                return redirect('register')->with('warning', 'Cette E-mail est déjà utilisé.');
            }
        }

        $user = new User();
        $user->name = $validData['name'];
        $user->email = $validData['email'];
        $user->password = Hash::make($validData['password']);

        $user->save();

        return redirect('login')->with('success', 'Votre compte à bien été crée. Vous pouvez dés maintenant vous identifer.');
     }
}
