<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use \DB;

class AuthController extends Controller
{
    const REFRESH_TOKEN = 'refreshToken';

    public function register(Request $request)
    {
        $valid_data = $request->validate([
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'ctftime_id' => 'nullable|unique:users',
            'password' => 'required|confirmed',
            'captcha' => 'required'
        ]);

        $response = $this->checkReCaptcha($valid_data['captcha']);

        if (!json_decode((string)$response->getBody())->success) {
            return response(
                [
                    'message' => 'reCAPTCHA error : ' . json_decode(
                            (string)$response->getBody(), true
                        )['error-codes'][0]
                ],
                400
            );
        }

        $valid_data['password'] = bcrypt($request['password']);

        $user = User::create($valid_data);
        $accessToken = $user->createToken('auth-token')->accessToken;

        VerificationController::send($user);

        return response(
            [
                'message' => 'please verify your email before login.',
                'access_token' => $accessToken
            ],
            200
        );
    }

    public function login(Request $request)
    {

        $login_data = $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
                'captcha' => 'required'
            ]
        );

        if (!auth()->attempt(['email' => $login_data['email'], 'password' => $login_data['password']])) {
            return response(['message' => 'wrong email/password'], 400);
        }

        if (auth()->user()->email_verified_at == null) {
            $accessToken = auth()->user()->createToken('auth-token')->accessToken;

            return response([
                'message' => 'please verify your email before login.',
                'access_token' => $accessToken
            ], 403);
        }

        $response = $this->checkReCaptcha($login_data['captcha']);

        if (!json_decode((string)$response->getBody())->success) {
            return response(
                [
                    'message' => 'reCAPTCHA error : ' . json_decode(
                            (string)$response->getBody(), true
                        )['error-codes'][0]
                ],
                400
            );
        }

        $data = [
            'username' => $login_data['email'],
            'password' => $login_data['password'],
            'client_id' => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type' => 'password'
        ];

        $request = app('request')->create('/oauth/token', 'POST', $data);
        $response = app('router')->prepareResponse($request, app()->handle($request));


        $data = json_decode($response->getContent());

        return response(
            [
//                'user' => auth()->user(),
                'access_token' => $data->access_token,
                'expires_in' => $data->expires_in
            ], 200)
            ->cookie(self::REFRESH_TOKEN, $data->refresh_token, 43200, null, null, false, true);

    }


    public function logout(Request $request)
    {
        $accessToken = auth()->user()->token();

        DB::connection()
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        $cookie = \Cookie::forget(self::REFRESH_TOKEN);

        return response(null, 204)->withCookie($cookie);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie(self::REFRESH_TOKEN);

        $data = [
            'refresh_token' => $refreshToken,
            'client_id' => env('PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSWORD_CLIENT_SECRET'),
            'grant_type' => 'refresh_token'
        ];

        $request = app('request')->create('/oauth/token', 'POST', $data);
        $response = app('router')->prepareResponse($request, app()->handle($request));

        $data = json_decode($response->getContent());

        return response(
            [
                'access_token' => $data->access_token,
                'expires_in' => $data->expires_in
            ], 200)
            ->cookie(self::REFRESH_TOKEN, $data->refresh_token, 43200, null, null, false, true);
    }


    public function checkReCaptcha(string $captcha)
    {
        $client = new Client();
        return $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params' =>
                [
                    'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
                    'response' => $captcha
                ]
            ]
        );
    }

    public function test()
    {
        app()->withCookie(cookie('name', 'value', 200));

    }

}
