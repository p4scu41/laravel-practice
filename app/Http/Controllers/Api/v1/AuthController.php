<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::once($request->only(['email', 'password']))) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $token = Auth::user()->createToken('token');

        return ['token' => $token->plainTextToken];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        Auth::user()->currentAccessToken()->delete();

        return Response::noContent();
    }
}
