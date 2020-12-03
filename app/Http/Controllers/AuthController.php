<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
     *
     * @return JsonResponse
     */
    public function login()
    {
        session_start();

        if (isset($_SESSION['locked'])) {
            Log::debug($_SESSION['locked']);

            $timeDiff = time() - $_SESSION['locked'];
            Log::debug($timeDiff);

            if ($timeDiff > 10) {

                Log::debug($_SESSION['locked']);
                Log::debug($_SESSION['login_attempts']);

                unset($_SESSION['locked']);
                unset($_SESSION['login_attempts']);
            }
        }

        $credentials = request(['email', 'password']);

        if (isset($_SESSION['login_attempts'])) {
            if ($_SESSION['login_attempts'] > 2) {

                $_SESSION['locked'] = time();
                Log::debug($_SESSION['locked']);

                return response()->json(['error' => 'You need to wait 10 seconds to login'], 401);
            }
        }

        if (! $token = auth()->attempt($credentials)) {

            $_SESSION['login_attempts'] += 1;

            Log::debug($credentials);
            Log::debug($_SESSION['login_attempts']);

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
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
