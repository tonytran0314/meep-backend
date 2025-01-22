<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Requests\v1\LoginRequest;
use App\Http\Requests\v1\SignupRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Exception;

class AuthenticationController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            
            return $this->success(null, 'Login successful!');
        } else {
            return $this->error(null, 'Wrong email or password.', 401);
        }
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        return $this->success(null, 'Successfully logged out!');
    }

    public function signup(SignupRequest $request) {
        try {
            $username = strtolower(str_replace(' ', '', $request->name));
            while (User::where('username', $username)->exists()) {
                // Append 6 random alphanumeric characters
                $username = $username . Str::random(6);
            }

            User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'password' => $request->password
            ]);
    
            return $this->success(null, 'User created successfully');

        } catch (Exception $error) {
            return $this->error(null, 'Failed to create user', 500);
        }
    }
}
