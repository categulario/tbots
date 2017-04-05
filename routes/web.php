<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return [
        'ok' => true,
    ];
});

$app->get('/{bot}', function (Illuminate\Http\Request $request, $bot) use ($app) {
    Log::debug('request', $request->all());

    return ['bot' => $bot];
});
