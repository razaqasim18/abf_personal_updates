<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// old once working
// Broadcast::routes(['middleware' => ['web', 'auth:admin']]);

// Broadcast::channel('adminchannel.{id}', function ($admin, $id) {
//     return (int) $admin->id === (int) $id;
// }, ['guards' => ['admin']]);



Broadcast::channel('vendorchannel.{id}', function ($user, $id) {
    Log::info("Auth attempt for user channel: user={$user->id}, param={$id}");
    return $user instanceof \App\Models\User && $user->id == $id;
});
Broadcast::channel('adminchannel.{id}', function ($user, $id) {
    // Log::info("Auth attempt for admin channel: admin={$user->id}, param={$id}");
    return $user instanceof \App\Models\Admin && $user->id == $id;
});
