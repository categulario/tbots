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
        $results = collect(json_decode(file_get_contents(__DIR__.'/../resources/datamining/preview.json')))->filter(function ($item) use ($request) {
            return strpos(strtolower($item->fqn), strtolower($request->input('inline_query.query'))) >= 0;
        })->map(function ($item) {
            return [
                'type' => 'article',
                'id' => $item->id,
                'thumb_url' => 'https://tbots.categulario.tk/mountain.png',
                'title' => $item->name,
                'description' => $item->fqn,

                'input_message_content' => [
                    'message_text' => "<strong>{$item->fqn}</strong><br><a href='https://www.mountain-forecast.com/peaks/{$item->id}/forecasts/{$item->height}'>{$item->name}</a>",
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => false,
                ],
            ];
        })->take(20);

        return [
            'method' => 'answerInlineQuery',

            'inline_query_id' => $request->input('inline_query.id'),
            'cache_time' => 0,
            'results' => $results,
        ];
    }

    return ['ok' => true];
});
