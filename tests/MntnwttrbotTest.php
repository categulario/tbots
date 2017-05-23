<?php

class MntnwttrbotTest extends TestCase
{
    public function testInlineQuery()
    {
        $this->post('/mntnwttrtoken', [
            "update_id" => 725286433,
            "inline_query" => [
                "id" => "267138557323649014",
                "from" => [
                    "id" => 62198042,
                    "first_name" => "Abraham",
                    "last_name" => "Toriz Cruz",
                    "username" => "categulario"
                ],
                "query" => "spire",
                "offset" => ""
            ]])->seeJsonEquals([
                'method' => 'answerInlineQuery',
                'inline_query_id' => '267138557323649014',
                'cache_time' => 0,
                'results' => [
                    [
                        'type' => 'article',
                        'id' => 'Avalanche-Spire',
                        'thumb_url' => 'https://tbots.categulario.tk/mountain.png',
                        'title' => 'Avalanche Spire',
                        'description' => 'Alaska/Yukon Ranges > Alaska Range > Avalanche Spire',
                        'input_message_content' => [
                            'message_text' => "Forecast for <b>Alaska/Yukon Ranges > Alaska Range > Avalanche Spire</b> <a href=\"https://www.mountain-forecast.com/peaks/Avalanche-Spire/forecasts/2905\">here</a>",
                            'parse_mode' => 'HTML',
                            'disable_web_page_preview' => false,
                        ],
                    ],
                ],
            ]);
    }

    public function testMessage()
    {
        $this->post('/mntnwttrtoken', [
            "update_id" => 725286431,
            "message" => [
                "message_id" => 21,
                "from" => [
                    "id" => 62198042,
                    "first_name" => "Abraham",
                    "last_name" => "Toriz Cruz",
                    "username" => "categulario"
                ],
                "chat" => [
                    "id" => 62198042,
                    "first_name" => "Abraham",
                    "last_name" => "Toriz Cruz",
                    "username" => "categulario",
                    "type" => "private"
                ],
                "date" => 1491926391,
                "text" => "Avalanche"
            ]
        ])->seeJsonEquals([
            'method'       => 'sendMessage',
            'chat_id'      => 62198042,
            'text'         => 'Mountains containing the text *Avalanche*',
            'parse_mode'   => 'Markdown',
            'reply_markup' => [
                'inline_keyboard' => [
                    [[
                        'text' => 'Avalanche Spire',
                        'callback_data'  => 'mountain:Avalanche-Spire',
                    ]],
                ],
            ],
        ]);
    }

    public function testMessageMntnNotFound()
    {
        $this->post('/mntnwttrtoken', [
            "update_id" => 725286431,
            "message" => [
                "message_id" => 21,
                "from" => [
                    "id" => 62198042,
                    "first_name" => "Abraham",
                    "last_name" => "Toriz Cruz",
                    "username" => "categulario"
                ],
                "chat" => [
                    "id" => 62198042,
                    "first_name" => "Abraham",
                    "last_name" => "Toriz Cruz",
                    "username" => "categulario",
                    "type" => "private"
                ],
                "date" => 1491926391,
                "text" => "Pico"
            ]
        ])->seeJsonEquals([
            'method'       => 'sendMessage',
            'chat_id'      => 62198042,
            'text'         => "Can't find any mountain containing the text *Pico*",
            'parse_mode'   => 'Markdown',
        ]);
    }

    public function testCallbackDataMntn()
    {
        $this->post('/mntnwttrtoken', [
            "update_id" => 725286432,
            "callback_query" => [
                "id" => "267138556421820874",
                "from" => [
                    "id" => 62198042,
                    "first_name" => "Abraham",
                    "last_name" => "Toriz Cruz",
                    "username" => "categulario"
                ],
                "message" => [
                    "message_id" => 22,
                    "from" => [
                        "id" => 317868014,
                        "first_name" => "mntnwttrbot",
                        "username" => "mntnwttrbot"
                    ],
                    "chat" => [
                        "id" => 62198042,
                        "first_name" => "Abraham",
                        "last_name" => "Toriz Cruz",
                        "username" => "categulario",
                        "type" => "private"
                    ],
                    "date" => 1491926392,
                    "text" => "Si, puedo responder"
                ],
                "chat_instance" => "4784830299483813229",
                "data" => "mountain:Avalanche-Spire"
            ]
        ])->seeJsonEquals([
            'method'       => 'editMessageText',
            'message_id'   => 22,
            'chat_id'      => 62198042,
            'text'         => "Available heights for *Avalanche Spire*",
            'parse_mode'   => 'Markdown',
            'reply_markup' => [
                'inline_keyboard' => [
                    [[
                        'text' => 'Summit (2905)',
                        'callback_data'  => 'height:2905,mountain:Avalanche-Spire',
                    ]],
                    [
                        [
                            'text' => '2750',
                            'callback_data'  => 'height:2750,mountain:Avalanche-Spire',
                        ],
                        [
                            'text' => '2500',
                            'callback_data'  => 'height:2500,mountain:Avalanche-Spire',
                        ],
                    ],
                    [
                        [
                            'text' => '2250',
                            'callback_data'  => 'height:2250,mountain:Avalanche-Spire',
                        ],
                        [
                            'text' => '2000',
                            'callback_data'  => 'height:2000,mountain:Avalanche-Spire',
                        ],
                    ],
                    [
                        [
                            'text' => '1750',
                            'callback_data'  => 'height:1750,mountain:Avalanche-Spire',
                        ],
                        [
                            'text' => '1500',
                            'callback_data'  => 'height:1500,mountain:Avalanche-Spire',
                        ],
                    ],
                    [
                        [
                            'text' => '1250',
                            'callback_data'  => 'height:1250,mountain:Avalanche-Spire',
                        ],
                        [
                            'text' => '1000',
                            'callback_data'  => 'height:1000,mountain:Avalanche-Spire',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testCallbackDataHeight()
    {
        $this->post('/mntnwttrtoken', [
            "update_id" => 725286432,
            "callback_query" => [
                "id" => "267138556421820874",
                "from" => [
                    "id" => 62198042,
                    "first_name" => "Abraham",
                    "last_name" => "Toriz Cruz",
                    "username" => "categulario"
                ],
                "message" => [
                    "message_id" => 22,
                    "from" => [
                        "id" => 317868014,
                        "first_name" => "mntnwttrbot",
                        "username" => "mntnwttrbot"
                    ],
                    "chat" => [
                        "id" => 62198042,
                        "first_name" => "Abraham",
                        "last_name" => "Toriz Cruz",
                        "username" => "categulario",
                        "type" => "private"
                    ],
                    "date" => 1491926392,
                    "text" => "Si, puedo responder"
                ],
                "chat_instance" => "4784830299483813229",
                "data" => "height:2905,mountain:Avalanche-Spire"
            ]
        ])->seeJsonEquals([
            'method'       => 'editMessageText',
            'message_id'   => 22,
            'chat_id'      => 62198042,
            'text'         => "The forecast at 2905m for *Avalanche Spire* is almost ready",
            'parse_mode'   => 'Markdown',
            'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'callback_data' => 'var',
                            'text' => 'foo',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
