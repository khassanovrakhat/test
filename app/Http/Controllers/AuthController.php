<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Validator;
use \Firebase\JWT\JWT;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // private $key = "secretkey";
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        
        $auth = (object)$request->all();
        $validator = Validator::make($request->all(), [
            'iin' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $which = "";
        if($user = Student::where('iin', $auth->iin)->first()){
            if(!password_verify(($request->password), $user->password)){
                return response()->json(['iin' => 'Unauthorized'], 401);
            }
            $which = 'Student';
        }else if($user = Teacher::where('iin', $auth->iin)->first()){
            if(!password_verify(($request->password), $user->password)){
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $which = 'Teacher';
        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = [
            "iss" => "utopian",
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => [
                "user_id" => $user->id,
                "user_type" => $which,
            ]];
        $jwt = JWT::encode($token, env('JWT_SECRET'));
        return response()->json([
            'token'=>$jwt,
        ]);
    }

}
