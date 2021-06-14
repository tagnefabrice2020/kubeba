<?php

namespace App\Http\Controllers\User;

use App\Events\ForgotPassword;
use App\Events\NewUser;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends ApiController
{
	/**
     * @OA\Post(
     *      path="/v1/signup",
     *      operationId="Register user",
     *      tags={"Sign up"},
     *		@OA\RequestBody(
     *			required=true,
     *			description="Pass user information to register",
     *    		@OA\JsonContent(
	 *       		required={"email","password","firt_name","last_name"},
	 *				@OA\Property(property="first_name", type="string", format="integer", example="Fabrice"),
	 *				@OA\Property(property="last_name", type="string", format="integer", example="Tagne"),
	 *       		@OA\Property(property="email", type="string", format="email", example="tagnefabrice@gmail.com"),
	 *				@OA\Property(property="phone_number", type="string", format="integer", example="656874852"),
	 *       		@OA\Property(property="password", type="string", format="password", example="password"),
	 *       		@OA\Property(property="account_type", type="integer", example="client"),
	 *    		),
     *		),
	 *
     *      summary="Register new user",
     *      description="Returns user created",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
    */
    /**
    * @param Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function signup(Request $r)
    {
    	$this->validate($r,[ 
    		'first_name' => 'required',
    		'last_name' => 'required',
    		'email' => 'required|unique:users',
    		'password' => 'required',
    		'phone_number' => 'required|unique:users',
    		'account_type' => 'required'
    	]);

    	$user = new User;
    	$user->uuid = Str::orderedUuid();
    	$user->first_name = $r->first_name;
    	$user->email_verification_token = User::generateVerificationCode();
    	$user->last_name = $r->last_name;
    	$user->email = $r->email;
    	$user->phone_number = $r->phone_number;
    	$user->password = Hash::make($r->password);
    	$user->email_verification_token_expires_at = Carbon::now()->addMinute(5);

    	$user->save();

    	$role = Role::where('name', $r->account_type)->first();
    	$user->attachRole($role);

    	event(new NewUser($user));

    	return $this->showOne($user, 201);
    
    }
    /**
     * @OA\Get(
     *      path="/api/v1/{email}/confirmAccount/{token}",
     *      operationId="Confirm your account",
     *      tags={"Confirm Account"},
     *		@OA\RequestBody(
     *			required=true,
     *			description="Pass user email and email verification token",
     *    		@OA\JsonContent(
	 *       		required={"email","token"},
	 *				@OA\Property(property="token", type="string", format="integer", example=""),
	 *       		@OA\Property(property="email", type="string", format="email", example="tagnefabrice@gmail.com")
	 *    		),
     *		),
	 *
     *      summary="Confirm your account",
     *      description="Returns message",
     *      @OA\Response(
     *          response=200,
     *          description="User verified",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
    */
    /**
    * @param $token User verification token sent for account confirmation
    * @param $email The email of the account owner verification
    * @return \Illuminate\Http\Response
    */
    public function confirmAccount($email, $token)
    {
    	$user = User::where('email', $email)
    				->where('email_verification_token', $token)
    				->first();
    	if (!empty($user) && $user->email_verification_token_expires_at > Carbon::now()) {
    		$user->email_verification_token = null;
    		$user->email_verified_at = now();
    		$user->save();
    		return $this->showSuccessMessage('account verified', 200); 
    	} 
    	return $this->showErrorMessage('account verification token expired', 403); 
    }
    /**
     * @OA\Get(
     *      path="/api/v1/sendEmailVerificationToken/{email}",
     *      operationId="Send EmailVerification Token",
     *      tags={"Confirm Account"},
     *		@OA\RequestBody(
     *			required=true,
     *			description="Pass user email",
     *    		@OA\JsonContent(
	 *       		required={"email"},
	 *       		@OA\Property(property="email", type="string", format="email", example="tagnefabrice@gmail.com")
	 *    		),
     *		),
	 *
     *      summary="Send EmailVerification Token",
     *      description="Returns message",
     *      @OA\Response(
     *          response=200,
     *          description="User verified",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
    */
    /**
    * @param $email The email of the account owner verification
    * @return \Illuminate\Http\Response
    */
    public function sendEmailVerificationToken($email)
    {
    	$user = User::where('email', $email)
    				->whereNull('email_verified_at')
    				->first();
    	$user->email_verification_token_expires_at = Carbon::now()->addMinute(5);
    	$user->email_verification_token = User::generateVerificationCode();
    	$user->save();
    	if (!empty($user)) {
    		event(new NewUser($user));
    		return $this->showSuccessMessage('Email sent', 200);  
    	}
    }
      /**
     * @OA\Get(
     *      path="/api/v1/forgotpassword",
     *      operationId="Forgot Password",
     *      tags={"Forgot Password"},
     *		@OA\RequestBody(
     *			required=true,
     *			description="Pass user email",
     *    		@OA\JsonContent(
	 *       		required={"email"},
	 *       		@OA\Property(property="email", type="string", format="email", example="tagnefabrice@gmail.com")
	 *    		),
     *		),
	 *
     *      summary="Forgot Password",
     *      description="Returns message",
     *      @OA\Response(
     *          response=200,
     *          description="Verify your email to confirm your account",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
    */
    /**
    * @param Illuminate\Http\Request The email of the account owner verification
    * @return \Illuminate\Http\Response
    */
    public function forgotPassword(Request $r)
    {
    	$this->validate($r, [
    		'email' => 'required'
    	]);
    	$user = User::where('email', $r->email)->first();
    	if (!empty($user)) {
    		event(new ForgotPassword($user));
    		return $this->showSuccessMessage('Verify your email to confirm your account', 200);  
    	}
    	return $this->showErrorMessage('Email not found', 404); 
    }
    /**
     * @OA\Post(
     *      path="/api/v1/resetPassword",
     *      operationId="Reset Password",
     *      tags={"Forgot Password"},
     *		@OA\RequestBody(
     *			required=true,
     *			description="Password revovered",
     *    		@OA\JsonContent(
	 *       		required={"email", "password", "password_confirmation"},
	 *				@OA\Property(property="password", type="string", format="password", example="PassWord"),
	 *				@OA\Property(property="password_confirmation", type="string", format="password", example="PassWord"),
	 *       		@OA\Property(property="email", type="string", format="email", example="tagnefabrice@gmail.com")
	 *    		),
     *		),
	 *
     *      summary="Forgot Password",
     *      description="Returns message",
     *      @OA\Response(
     *          response=200,
     *          description="Reset your password to recover your account",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
    */
    /**
    * @param Illuminate\Http\Request The email of the account owner verification
    * @return \Illuminate\Http\Response
    */
    public function resetPassword(Request $r)
    {
    	$this->validate($r, [
    		'email' => 'required', 
    		'password' => 'required|confirmed|min:6', 
    	]);
    	$user = User::where('email', $r->email)->first();
    	if (!empty($user)) {
    		$user->password = Hash::make($r->password);
    		$user->save(); 
    		return $this->showSuccessMessage('Password revovered', 200);  
    	}
    	return $this->showErrorMessage('Password recovery failed', 404); 
    }
    /**
     * @OA\Patch(
     *		security={{"passport": {}}},
     *      path="/v1/changePassword",
     *      operationId="Change Password",
     *      tags={"Change Password"},
     *		@OA\RequestBody(
     *			required=true,
     *			description="Change Password",
     *    		@OA\JsonContent(
	 *       		required={"email", "password", "password_confirmation"},
	 *				@OA\Property(property="password", type="string", format="password", example="password1"),
	 *				@OA\Property(property="password_confirmation", type="string", format="password", example="password1"),
	 *       		@OA\Property(property="current_password", type="string", format="password", example="password")
	 *    		),
     *		),
	 *
     *      summary="Change Password",
     *      description="password modified",
     *      @OA\Response(
     *          response=200,
     *          description="Set a new password for your account",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
    */
    /**
    * @param Illuminate\Http\Request The email of the account owner verification
    * @return \Illuminate\Http\Response
    */
    public function changePassword(Request $r)
    {
    	$this->validate($r, [
    		'password' => 'required|min:6|confirmed', 
    		'current_password' => ['required', new MatchOldPassword],
    	]);
    	User::find(auth()->guard('api')->user()->id)->update(['password'=> Hash::make($r->new_password)]);
    	return $this->showSuccessMessage('password modified', 200);
    }

}
