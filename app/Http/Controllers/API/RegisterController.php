<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
Use App\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class RegisterController extends BaseController
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        
        if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());
        }
        
        
        $input = $request->all();
        $user =  DB::table('users')
                    ->where('email', $input['email'])
                    ->first();
        if($user){
            return $this->sendError('Email already registered.', $validator->errors());
        }

        $input['password'] = bcrypt($input['password']);
        $input['role'] = 1;
        $user = User::create($input);
        $user->roles()->attach(Role::where('name', 'guest')->first());

        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['email'] = $user->email;
        
        return $this->sendResponse($success, 'User register successfully.');
    }
    
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')-> accessToken;
            $success['email'] = $user->email;
            
            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
    
    public function logout()
    {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
        ->where('access_token_id', $accessToken->id)
        ->update([
        'revoked' => true
        ]);
        
        $accessToken->revoke();
        return response()->json([
        'message' => 'Successfully logged out'
        ]);
    }
}
