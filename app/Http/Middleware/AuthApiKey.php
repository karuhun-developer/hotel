<?php

namespace App\Http\Middleware;

use App\Models\User\ApiKey;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if has header
            if (! $request->headers->has('X-API-KEY')) {
                return $this->checkAuth($request, $next);
            }
            $user = Crypt::decrypt($request->header('X-API-KEY'));

            // Validate API KEY
            if (! isset($user['salt']) || ! isset($user['id'])) {
                return $this->checkAuth($request, $next);
            }

            // Check if API KEY exists
            $apiKey = ApiKey::find($user['id']);
            if (! $apiKey) {
                return $this->checkAuth($request, $next);
            }

            // Check salt
            if (! password_verify($user['salt'], $apiKey->salt)) {
                return $this->checkAuth($request, $next);
            }

            // Check data
            $user = $apiKey->user;

            // Check if user exists
            if (! $user) {
                return $this->checkAuth($request, $next);
            }

            // Check if api key is expired
            if ($apiKey->expired_at && $apiKey->expired_at->isPast()) {
                return $this->checkAuth($request, $next);
            }

            // Update api key last used at
            $apiKey->last_used_at = now();
            $apiKey->save();

            Auth::login($user);

            $request->attributes->add(['user' => $user]);

            return $next($request);
        } catch (\Throwable $th) {
            return $this->checkAuth($request, $next);
        }
    }

    public function checkAuth(Request $request, Closure $next)
    {
        // Check if has Bearer token
        if ($request->bearerToken()) {
            return $this->validateBearerToken($request, $next);
        }

        if (Auth::check()) {
            return $next($request);
        } else {
            throw new HttpResponseException(response()->json([
                'code' => 401,
                'message' => 'Unauthenticated.',
            ], 401));
        }
    }

    public function validateBearerToken(Request $request, Closure $next)
    {
        // Check if token is valid
        if ($token = PersonalAccessToken::findToken($request->bearerToken())) {
            $user = $token->tokenable;

            Auth::login($user);
            $request->attributes->add(['user' => $user]);

            return $next($request);
        } else {
            throw new HttpResponseException(response()->json([
                'code' => 401,
                'message' => 'Unauthenticated.',
            ], 401));
        }
    }
}
