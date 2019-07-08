<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModelTanahnya;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpired\Exception;
// use Tymon\JWTAuth\Exceptions\TokenInvalid\Exception;
use Tymon\JWTAuth\JWTAuth;
// use Tymon\JWTAuth\Facades\JWTAuthor;

class AuthController extends Controller
{
    /**
     * @var TymonJWTAuthJWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function loginPost(Request $request)
    {
        // $this->validate($request, [
        //     'username'    => 'required',
        //     'password' => 'required',
        // ]);

        $username =  $request->json()->get('username');
        $password =  $request->json()->get('password');


        // return response()->json($response);
        
        try {
            if (! $token = $this->jwt->attempt(array('username' => $username, 'password' => $password))) {
                return response()->json(['status' => 'user_not_found']);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['status' => 'token_expired']);
        } catch (TokenInvalidException $e) {
            return response()->json(['status' => 'token_invalid']);
        } catch (JWTException $e) {
            return response()->json(['status' => $e->getMessage()]);
        }

        $isAdmin = ModelTanahnya::isAdmin($username);
        if($isAdmin) $role = 'admin';
        else $role='users';
        $response = [
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'status' => 'login',
            compact('token')
        ];
        return response()->json($response);
    }

    public function AuthorizeArduino(Request $request){
        $nama_alat = $request->json()->get('nama_alat');
        $clientIP = "192.168.1.200";//$request->ip();
        $result = ModelTanahnya::AuthorizeArduino($nama_alat, $clientIP);
        return $result;
    }

    public function AuthorizeAdmin(Request $request){
        $username = $request->header('username');
        $result = ModelTanahnya::isAdmin($username);
        // return $result;
        if($result) return response('OKE');
        else return response('Ndak OKE');
    }
}