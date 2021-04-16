<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\user;
use App\Http\Controllers\ApiController\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ApiControllerAuth extends BaseController
{
    public function signin(Request $request) {
		if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
			$user = Auth::user();
			$success['token'] = $user->createToken($user->username)->accessToken;
			$success['username'] = $user->username;
			$success['pengguna'] = $user->pengguna;
			
			return $this->sendResponse($success,'User sign successfully');
		}else{
			return $this->sendError('Unauthorized.');
		}
    }
	
	public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'Successfully logged out', 'success' => true
            ]);
        }
    }
}
