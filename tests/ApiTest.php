<?php

class ApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexPage()
    {
        $this->get('/');

        $this->assertEquals(
            '{"ok":true}', $this->response->getContent()
        );
    }

    public function testInlineQuery()
    {
        $this->post('/bot', [
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
        $this->post('/bot', [
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
                        'callback_data'  => 'Avalanche-Spire',
                    ]],
                ],
            ],
        ]);
    }

    public function testMessageMntnNotFound()
    {
        $this->post('/bot', [
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
}
