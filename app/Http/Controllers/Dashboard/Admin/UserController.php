<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(CreateUserRequest $request){

        $data = $request->validated();

        $user = User::create([
            "name"=>$data['name'],
            "email"=>$data['email'],
            "password"=>Hash::make($data['password'])
        ]);

        $user->assignRole('supervisor');

        return $this->respondSuccess('User successfully created!');

    }

    public function list(){

        $users = User::role('supervisor')->get();

        return $this->apiResponse([
            'success'=>true,
            'message'=>'Users successfully fetched!',
            'result'=>$users
        ]);
    }

    public function delete(User $user){
        
        $user->delete();

        return $this->respondSuccess('User successfully deleted!');

    }
}
