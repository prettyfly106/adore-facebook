<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\User;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * User login
     */
    public function postLogin(Request $request)
    {
        Log::debug(__METHOD__.' - validate input');
        $this->validate($request, [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

        Log::debug(__METHOD__.' - validate user and create token');
        $token = JWTAuth::attempt($request->only('username', 'password'));
        if (!$token)
        {
            return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }
        else
        {
            return response()->json(compact('token'));
        }
    }

    /**
     * User logout
     */
    public function postLogout(Request $request)
    {
        // Could be done by client code by removing the token from client side
    }

    /**
     * Register new user
     */
    public function postRegister(Request $request)
    {
        Log::debug(__METHOD__.' - validate input');
        // Todo: refactor for trait to create user
        $this->validate($request, [
            'username' => 'required|max:45|unique:users,username',
            'password' => 'required|min:6|max:45',
            'image' => 'required|max:100',
            'name' => 'required|max:10',
            'phone' => 'required|numeric',
            'client_id' => 'required|integer',
            'dateOfBith' => 'required|date',
            'email' => 'required|email',
            'address' => 'required|max:250'
        ]);

        try
        { 
            Log::debug(__METHOD__.' - create new user');
            $user = new User;
            $user->fill($request->all());
            $user->dateOfBith = date('Y-m-d H:i:s', strtotime($request->input('dateOfBith')));
            $user->password = bcrypt($request->input('password'));
            $user->save();
        }
        catch (Exception $e)
        {
           return response()->json(['error' => 'User already exists.'], Response::HTTP_CONFLICT);
        }
        Log::debug(__METHOD__.' - create token');
        $token = JWTAuth::fromUser($user);
        Log::debug(__METHOD__.' - token is: '.$token);
        return response()->json(compact('token'));
    }
    
    /**
     * Send email when forgot password
     */
    public function postEmailPassword(Request $request)
    {
        Log::debug(__METHOD__.' - validate input');
        $this->validate($request, ['email' => 'required|email']);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        Log::debug(__METHOD__.' - send reset link');
        $response = Password::broker()->sendResetLink($request->only('email'));

        if ($response == Password::RESET_LINK_SENT)
        {
            return response()->json(trans($response));
        }
        else
        {
            return response()->json(['error' => trans($response)], Response::HTTP_BAD_REQUEST);
        }
    }
    
    /**
     * Update password
     */
    public function postResetPassword(Request $request)
    {
        Log::debug(__METHOD__.' - validate input');
        $this->validate($request, [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed', // require password_confirmation
            ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        Log::debug(__METHOD__.' - reset password');
        $response = Password::broker()->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = bcrypt($password);
                    $user->save();
                    Log::debug(__METHOD__.' - create token');
                    $token = JWTAuth::fromUser($user);
                }
        );

        // Send result
        if ($response == Password::PASSWORD_RESET)
        {
            return response()->json(compact('token'));
        }
        else
        {
            return response()->json(['error' => trans($response)], Response::HTTP_BAD_REQUEST);
        }
    }
}
