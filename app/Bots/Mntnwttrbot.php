<?php

namespace App\Bots;

use Illuminate\Http\Request;
use App\Query;
use App\Jobs\GetForecast;
use Log;

class Mntnwttrbot extends Bot
{
    public function hello(Request $request)
    {
        return [
            'name' => 'Mntnwttrbot',
        ];
    }

    public function inlineQuery(Request $request)
    {
        $query = $request->input('inline_query.query');

        $results = Query::search($query)->map(function ($item) {
            return [
                'type' => 'article',
                'id' => $item->id,
                'thumb_url' => 'https://tbots.categulario.tk/mountain.png',
                'title' => $item->name,
                'description' => $item->fqn,

                'input_message_content' => [
                    'message_text' => "Forecast for <b>".$item->fqn."</b> <a href=\"https://www.mountain-forecast.com/peaks/{$item->id}/forecasts/{$item->height}\">here</a>",
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => false,
                ],
            ];
        })->take(20)->values()->toArray();

        return [
            'method' => 'answerInlineQuery',

            'inline_query_id' => $request->input('inline_query.id'),
            'cache_time' => 0,
            'results' => $results,
        ];
    }

    public function message(Request $request)
    {
        $query = $request->input('message.text');

        $results = Query::search($query)->map(function ($item) {
            return [[
                'text' => $item->name,
                'callback_data' => 'mountain:'.$item->id,
            ]];
        })->take(5)->values()->toArray();

        if (count($results) == 0) {
            return [
                'method'       => 'sendMessage',
                'chat_id'      => $request->input('message.chat.id'),
                'text'         => "Can't find any mountain containing the text *$query*",
                'parse_mode'   => 'Markdown',
            ];
        }

        return [
            'method'       => 'sendMessage',
            'chat_id'      => $request->input('message.chat.id'),
            'text'         => "Mountains containing the text *$query*",
            'parse_mode'   => 'Markdown',
            'reply_markup' => [
                'inline_keyboard' => $results,
            ],
        ];
    }

    public function callbackQuery(Request $request)
    {
        $callback_data = $request->input('callback_query.data');
        $data = [];

        foreach (explode(',', $callback_data) as $piece) {
            $parts = explode(':', $piece);
            $data[$parts[0]] = $parts[1];
        }

        $peak = Query::find($data['mountain']);

        if (starts_with($callback_data, 'mountain')) {
            $heights = [
                [[
                    'text' => 'Summit ('.$peak->height.')',
                    'callback_data' => 'height:'.$peak->height.',mountain:'.$peak->id,
                ]]
            ];

            $nh = ((int)($peak->height/250))*250;

            if ($nh == $peak->height) {
                $nh = $peak->height - 250;
            }
            $height = $nh;
            $h = 0;

            while ($h < 4 && $height >= 0) {
                $hrow = [];

                $i = 0;
                while ($i<2 && $height >= 0) {
                    $hrow[] = [
                        'text' => (string)$height,
                        'callback_data' => 'height:'.$height.',mountain:'.$peak->id,
                    ];
                    $height -= 250;
                    $i++;
                }

                $heights[] = $hrow;
                $h++;
            }

            return [
                'method'       => 'editMessageText',

                'chat_id'      => $request->input('callback_query.message.chat.id'),
                'message_id'   => $request->input('callback_query.message.message_id'),
                'text'         => "Available heights for *{$peak->name}*",
                'parse_mode'   => 'Markdown',
                'reply_markup' => [
                    'inline_keyboard' => $heights,
                ],
            ];
        } elseif (starts_with($callback_data, 'height')) {
            $height = explode(':', explode(',', $callback_data)[0])[1];

            dispatch(new GetForecast($peak->id, $height, $request->input('callback_query.message.chat.id')));

            $user = $request->input('callback_query.from.first_name');

            Log::debug("User {$user} requested forecast for {$peak->height} of {$peak->id}");

            return [
                'method'       => 'editMessageText',

                'chat_id'      => $request->input('callback_query.message.chat.id'),
                'message_id'   => $request->input('callback_query.message.message_id'),
                'text'         => "The forecast at {$height}m for *".$peak->name.'* is almost ready',
                'parse_mode'   => 'Markdown',
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            [
                                'text' => 'foo',
                                'callback_data' => 'var',
                            ],
                        ],
                    ],
                ],
            ];
        }
    }

}
