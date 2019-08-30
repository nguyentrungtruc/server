<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\Admin\AuthResource;

class AuthController extends Controller
{
	protected $admin, $employee;

	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login']]);
		$this->admin    = Role::admin();
		$this->employee = Role::employee();
	}
	/**
     * Log the user in (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
	//LOGIN
	public function login(Request $request)
	{
        $credentials = request(['email', 'password']);
        $token       = auth('api')->attempt($credentials);;
		
		if (!$token) {
			return response()->json(['error' => 'Unauthorized'], 403);
		}

		$user = auth('api')->user();

		if($user->role_id === $this->admin->id) {
			return $this->respondWithToken($token);
		}
		auth('api')->logout();
		return response()->json(['error' => 'Unauthorized'], 401);
	}
    /**
     * Active the user after register (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function me()
    {
    	$user = auth('api')->user();
        if($user->actived) {
    		if ($user->role_id === $this->admin->id) {
    			$res = [
    				'type'    => 'success',
    				'message' => 'Get Information Successfully!!!',
    				'data'    => new AuthResource($user)
    			];
    			return response($res, 200);
    		}
    		auth('api')->logout();
    	} else {
    		auth('api')->logout();
    		$res = [
    			'type'    => 'error',
    			'message' => 'Tài khoản chưa được kích hoạt. Vui lòng truy cập vào hộp thư để kích hoạt tài khoản',
    			'data'    => []
    		];
    		return response($res, 200);
    	}
    	return response('Unauthorized Error', 401);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
    	auth('api')->logout();
		$res = [
			'type'    => 'success',
			'message' => 'Successfully logged out',
			'data'    => []
		];
    	return response($res, 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
    	return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
    	return response()->json([
    		'access_token' => $token,
    		'token_type'   => 'bearer',
    		'expires_in'   => auth('api')->factory()->getTTL()*3600*1000
    	]);
    }
}
