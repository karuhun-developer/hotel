<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\User\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        // Try API Key authentication first
        if ($user = $this->authenticateViaApiKey($request)) {
            Auth::login($user);

            return $next($request);
        }

        // Try Bearer token authentication
        if ($user = $this->authenticateViaBearer($request)) {
            Auth::login($user);

            return $next($request);
        }

        // Check if already authenticated (session)
        if (Auth::check()) {
            return $next($request);
        }

        return $this->unauthenticatedResponse();
    }

    private function authenticateViaApiKey(Request $request): ?User
    {
        $apiKeyHeader = $request->header('X-API-KEY');

        if (! $apiKeyHeader) {
            return null;
        }

        try {
            $decrypted = Crypt::decrypt($apiKeyHeader);
        } catch (\Throwable) {
            return null;
        }

        if (! isset($decrypted['salt'], $decrypted['id'])) {
            return null;
        }

        $apiKey = ApiKey::find($decrypted['id']);

        if (! $apiKey) {
            return null;
        }

        if (! password_verify($decrypted['salt'], $apiKey->salt)) {
            return null;
        }

        if ($apiKey->expired_at?->isPast()) {
            return null;
        }

        $user = $apiKey->user;

        if (! $user) {
            return null;
        }

        // Update last used timestamp
        $apiKey->update(['last_used_at' => now()]);

        return $user;
    }

    private function authenticateViaBearer(Request $request): ?User
    {
        $bearerToken = $request->bearerToken();

        if (! $bearerToken) {
            return null;
        }

        $token = PersonalAccessToken::findToken($bearerToken);

        if (! $token) {
            return null;
        }

        return $token->tokenable;
    }

    private function unauthenticatedResponse(): Response
    {
        return response()->json([
            'code' => 401,
            'message' => 'Unauthenticated.',
        ], 401);
    }
}
