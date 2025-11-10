<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Confirm email if needed
        event(new Registered($user));

        // Assign default role
        $user->syncRoles('user');

        // Save activity
        activity()->performedOn($user)->causedBy($user)->event('Register')->log('Register');

        return $this->responseWithCreated([
            'token' => $user->createToken('API Token')->plainTextToken,
            'token_type' => 'bearer',
        ]);
    }
}
