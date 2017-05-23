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

$app->get('/{bot}', ['middleware' => 'bots', function (Illuminate\Http\Request $request, $bot) use ($app) {
    return $request->bot->hello($request);
}]);

$app->post('/{bot}', ['middleware' => 'bots', function (Illuminate\Http\Request $request, $bot) use ($app) {
    if ($request->input('inline_query')) {
        return $request->bot->inlineQuery($request);
    } elseif ($request->input('message')) {
        return $request->bot->message($request);
    } elseif ($request->input('callback_query')) {
        return $request->bot->callbackQuery($request);
    }

    return ['msg' => 'method not supported'];
}]);
