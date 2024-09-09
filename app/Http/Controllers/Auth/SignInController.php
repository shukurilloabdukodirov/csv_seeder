<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInProcessRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignInController extends Controller
{

    public function process(SignInProcessRequest $request){

        $credentials = $request->validated();
        
        if(!Auth::guard('web')->attempt($credentials)){
            return $this->respondUnAuthorized();
        }
        $user = User::where(['name'=>$credentials['name']])->first();

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addHours(24);

        $token->save();
        return $this->apiResponse(
            [
                'success' => true,
                'message' => 'Successessfully logged in!',
                "result"=>[
                    "token"=>$tokenResult->accessToken,
                    "user"=>$user,
                    "abilities"=>$user->abilities()
                ]
            ],
            200
        );
    }

}
