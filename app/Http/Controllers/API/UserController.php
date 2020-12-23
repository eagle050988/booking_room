<?php

namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
 
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    //
    public function show()
    {
        $user = Auth::user();
        
        return $this->sendResponse($user, 'User details successfully.');
    }
}
