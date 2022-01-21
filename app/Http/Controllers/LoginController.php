<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller {

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(Request $request): string
    {

        $request->validate(
            [
                'email'    => 'required|email',
                'password' => 'required'
            ]
        );

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password))
        {
            throw ValidationException::withMessages([
                'email' => [ 'The provided credentials are incorrect.' ],
            ]);
        }

        return $user->createToken($request->input('email'))->plainTextToken;
    }
}
