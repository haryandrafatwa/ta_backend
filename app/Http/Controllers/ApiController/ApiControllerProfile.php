<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiController\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ApiControllerProfile extends BaseController
{
    protected $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = User::find(Auth::id());
			if($user->pengguna == "dosen"){
				$user = User::with('tbl_dosen')->find(Auth::id());
			}else if($user->pengguna == "mahasiswa"){
				$user = User::with('tbl_mahasiswa')->find(Auth::id());
			}else{
				$user = User::with('tbl_koordinator')->find(Auth::id());
				
			}
            if(!$user){
                return response()->json([
                    'error' => true,
                    'message' => 'User not found'
                ]);
            }
            $this->user = $user;

            return $next($request);
        });
    }
	
	public function getUserDetails(){
        return $this->user;
    }
	
}
