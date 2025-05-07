<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Members;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $req)
    {
        $credentials = $req->only('phone_number','password');
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error'=>'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
{
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members',
            'phone_number' => 'required|regex:/^\\d{9,15}$/|unique:members',
            'password' => 'required|string|min:8',
        ]);

        $data['password'] = bcrypt($data['password']);
        $member = Members::create($data);

        $token = auth('api')->login($member);
        return response()->json([ 'access_token' => $token, 'expires_in' => auth('api')->factory()->getTTL()*60 ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }
}