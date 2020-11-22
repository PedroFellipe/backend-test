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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::get('user-profile', 'App\Http\Controllers\AuthController@userProfile');
});

Route::group(['middleware' => ['jwt.auth']], function () {

    Route::prefix('friendship_invitation')->group(function () {
        Route::get('', 'App\Http\Controllers\FriendshipInvitationController@index')->name('friendship_invitation.index');
        Route::post('', 'App\Http\Controllers\FriendshipInvitationController@store')->name('friendship_invitation.store');
        Route::put('/{id}', 'App\Http\Controllers\FriendshipInvitationController@update')->name('friendship_invitation.update');
        Route::delete('/{id}', 'App\Http\Controllers\FriendshipInvitationController@destroy')->name('friendship_invitation.destroy');
    });

    Route::prefix('friendship')->group(function () {
        Route::get('', 'App\Http\Controllers\FriendshipController@index')->name('friendship.index');
        Route::delete('/{id}', 'App\Http\Controllers\FriendshipController@destroy')->name('friendship.destroy');
    });

    Route::prefix('event')->group(function () {
        Route::post('', 'App\Http\Controllers\EventController@store')->name('event.store');
        Route::put('/{id}', 'App\Http\Controllers\EventController@update')->name('event.update');
        Route::delete('/{id}', 'App\Http\Controllers\EventController@destroy')->name('event.destroy');

        //events invitations
//        Route::get('/{id_event}/invitation', 'App\Http\Controllers\EventController@invitations')->name('event.invitation.invitations');

        Route::post('/{id_event}/invitation', 'App\Http\Controllers\EventController@inviteEvent')->name('event.invitation.inviteEvent');
        Route::put('/{id_event}/invitation/{id_event_invitation}', 'App\Http\Controllers\EventController@updateEventInvitation')->name('event.invitation.updateEventInvitation');

    });

    Route::prefix('user')->group(function () {
        Route::get('/event', 'App\Http\Controllers\EventController@myEvents')->name('user.myEvents');
        Route::get('/invitation', 'App\Http\Controllers\EventController@invitations')->name('user.invitations');

    });

});

Route::prefix('event')->group(function () {
    Route::get('', 'App\Http\Controllers\EventController@index')->name('event.index');
    Route::get('/{id}', 'App\Http\Controllers\EventController@show')->name('event.show');
});

Route::prefix('attachments')->group(function () {
    Route::get('/{id}', 'App\Http\Controllers\AttachmentsController@show')->name('file.show');
});
