<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\User;
use JWTAuth;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    /**
     * User login
     */
    protected $fbAPIUri = 'https://graph.facebook.com/v2.10/';//Config::get('constants.fbAPIUri');

    public function getLogin() {
      $user = JWTAuth::parseToken()->authenticate();
      return $user;
    }

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
            $user = User::where('username', $request->username)->first();
            return response()->json([
                  'status' => 'SUCCESS',
                  'token' => $token,
                  'user_info' => $user
              ], 200);
        }
    }

    public function facebookLogin(Request $request)
    {
      $client = new Client();
      $result = $client->get('https://graph.facebook.com/v2.10/debug_token', [
        'query' => [
            'input_token' => $request->access_token,
            'access_token' => $request->access_token
        ]
      ]);
      $response = json_decode($result->getBody()->getContents());
      $user = null;
      $token = null;
      if ($response->data) {
        $fb_id = $response->data->user_id;
        $client = new Client();
        $result = $client->get('https://graph.facebook.com/v2.10/me', [
          'query' => [
              'fields' => 'name,email,birthday,gender,picture.type(large)',
              'access_token' => $request->access_token
          ]
        ]);
        $fb_info = json_decode($result->getBody()->getContents());
        $user = User::where('fb_id',$fb_id)->orWhere('email','=',$fb_info->email)->first();
        if ($user==null && $fb_info != null) { // Tao
          Log::debug(__METHOD__.' - create new user');
          $user = new User;
          $user->username=$fb_info->name;
          $user->name=$fb_info->name;
          $user->image=$fb_info->picture->data->url;
          $user->email = $fb_info->email;
          $user->fb_id = $fb_id;
          $user->client_id = 1;
          $user->save();
        }
        else if ($user!=null && $user->fb_id == null) {
          $user->fb_id = $fb_id;
          $user->save();
        }
      }
      if ($user)
      $token = JWTAuth::fromUser($user);
      if (!$token)
      {
          return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
      }
      else
      {
          //return response()->json(compact('token'));
          return response()->json([
                'status' => 'SUCCESS',
                'token' => $token,
                'user_info' => $user
            ], 200);
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
            'image' => 'required|max:500',
            'name' => 'required|max:10',
            'phone' => 'required|numeric',
            'client_id' => 'required|integer',
            'dateOfBith' => 'required|date',
            'email' => 'required|email',
            'address' => 'required|max:250',
            'fb_id' => 'max:50'
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
