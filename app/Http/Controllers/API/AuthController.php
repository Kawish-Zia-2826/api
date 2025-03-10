<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $req){
            $validator  = Validator::make(
                $req->all(),[
                    'name'=>'required',
                    'email'=>'required|email|unique:users,email',
                    'password'=>'required'
                ]
                );
                if($validator->fails()){
                    return response()->json(
                        [
                            'status'=>false,
                            'message'=>'please check credential',
                            'errors'=>$validator->errors()->all()
                        ],
                        401
                    );

                }

               $user =  User::create([
                    'name'=>$req->name,
                    'email'=>$req->email,
                    'password'=>$req->password
                ]);

                return response()->json(
                    [
                        'status'=>true,
                        'message'=>'user created succefully',
                        'user'=>$user
                    ],
                    200
                );
    }


    public function login(Request $req){
        $validator  = Validator::make(
            $req->all(),[
                'password'=>'required',
                'email'=>'required|email'
            ]
            );
            if($validator->fails()){
                return response()->json(
                    [
                        'status'=>false,
                        'message'=>'you cant login ',
                        'errors'=>$validator->errors()->all()
                    ],
                    403
                );

            }
           


            if(Auth::attempt(['email' => $req->email, 'password' => $req->password])){

        
                $authUser = Auth::user();
                return response()->json(
                    [
                        'status'=>true,
                        'message'=>'user created succefully',
                        'token'=>$authUser->createToken('API Token')->plainTextToken,
                        'token_type'=>'bearer'
                    ],
                    200
                );
            }
            
          
            else{
                $usr = $req->user();
                return response()->json([
                    'status'=>false,
                    'message'=>'you are not our user please register yourself',
                    'user'=>$usr
                ],401);
            }
    }

    public function logout(Request $req){
            $user  = $req->user();
            $user->tokens()->delete();
            return response()->json(
                [
                    'status'=>true,
                    'message'=>'user logout succefully',
                    'user'=>$user
                    
                ],
                200
            );
    }
}
