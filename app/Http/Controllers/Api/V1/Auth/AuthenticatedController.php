<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthenticatedController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! auth()->attempt($request->only('email', 'password'))) {
            return $this->responseWithError('Your credentials are incorrect', 422);
        }

        // Save activity
        activity()->performedOn(auth()->user())->causedBy(auth()->user())->event('Login')->log('Login');

        // Delete all previous tokens
        auth()->user()->tokens()->delete();

        return $this->responseWithSuccess([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'token_type' => 'bearer',
        ]);
    }

    public function me(Request $request)
    {
        $ttl = now()->addMinutes(10);

        // If force refresh
        if ($request->has('forceRefresh') && $request?->forceRefresh) {
            Cache::forget('me:user'.$request->user()->id);
        }

        $user = Cache::remember('me:user'.$request->user()->id, $ttl, function () use ($request) {
            return $request->user()->load('roles');
        });

        return $this->responseWithSuccess($user);
    }

    public function logout()
    {
        // Save activity
        activity()->performedOn(auth()->user())->causedBy(auth()->user())->event('Login')->log('Logout');

        auth()->user()->tokens()->delete();

        return $this->responseWithSuccess(message: 'Successfully logged out');
    }
}
