<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//login
Route::post('/register','Api\AuthController@register');
Route::post('/login','Api\AuthController@login');
Route::post('/login/refresh', 'Api\AuthController@refresh');
Route::post('/logout','Api\AuthController@logout')
    ->middleware('auth:api');

Route::post('/password/email','Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset','Api\ResetPasswordController@reset');

//email verification
Route::get('/email/resend','Api\VerificationController@resend')
    ->name('verification.resend');
Route::get('/email/verify/{id}/{hash}','Api\VerificationController@verify')
    ->name('verification.verify');

//submission
Route::post('/submit','SubmissionController@store')
    ->middleware('auth:api','verified','participate');

//leaderboard
Route::get('/scoreboard', 'ScoreboardController@index');

//challenges
Route::get('/challenges','ChallengeController@index')
    ->middleware('auth:api','verified','participate');

//participants
Route::post('/participants','ParticipantController@store')
    ->middleware('auth:api','verified');
