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

$app->post('/{bot}', function (Illuminate\Http\Request $request, $bot) use ($app) {
    Log::debug("request to {$request->url()}", $request->all());

    if ($request->input('inline_query')) {
        $results = [];

        for ($i=0; $i<5; $i++) {
            $id = uniqid();

            $results[] = [
                'type' => 'photo',
                'id' => $id,
                'photo_url' => 'https://tbots.categulario.tk/mountain.png',
                'thumb_url' => 'https://tbots.categulario.tk/mountain.png',
                'title' => $id,
                'description' => 'Esta es la descripción',
                'caption' => 'Forecast for Cofre de perote',

                // 'reply_markup' => [
                    // 'inline_keyboard' => [
                        // [
                            // 'text' => 'foo',
                        // ],
                        // [
                            // 'text' => 'var',
                        // ],
                    // ],
                // ],
//
                // 'input_message_content' => [
                    // 'message_text' => 'message text',
                    // 'parse_mode' => 'HTML',
                    // 'disable_web_page_preview' => true,
                // ],
            ];
        }

        return [
            'method' => 'answerInlineQuery',

            'inline_query_id' => $request->input('inline_query.id'),
            'cache_time' => 0,
            'results' => $results,
        ];
    }

    return ['ok' => true];
});
