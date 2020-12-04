<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     * @param Request $request
     */
    public function login(Request $request)
    {
        session_start();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'required' => 'Le champs :attribute est requis.',
            'email' => 'Votre E-mail n\'est pas au bon format',
        ]);

        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator);
        }

        if (isset($_SESSION['locked'])) {

            $timeDiff = time() - $_SESSION['locked'];

            if ($timeDiff > 10) {
                unset($_SESSION['locked']);
                unset($_SESSION['login_attempts']);
            }
        }

        $credentials = request(['email', 'password']);

        if (isset($_SESSION['login_attempts'])) {
            if ($_SESSION['login_attempts'] > 2) {

                $_SESSION['locked'] = time();
                Log::debug($_SESSION['locked']);
                Log::debug($credentials);

                return redirect('login')
                    ->withErrors('Bloqué pendant 10s!');
            }
        }

        if (! $token = auth()->attempt($credentials)) {
            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 1;
            }

            $_SESSION['login_attempts'] += 1;

            return redirect('login')->withErrors('Attention!');
        }

        return redirect('/page')->with($this->respondWithToken($token));
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
