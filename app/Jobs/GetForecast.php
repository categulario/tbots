<?php

namespace App\Jobs;

use GuzzleHttp\Client;

class GetForecast extends Job
{
    public $peak;

    public $height;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($peak, $height)
    {
        $this->peak = $peak;
        $this->height = $height;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client([
            'base_uri' => 'https://api.telegram.org/bot123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11/',
        ]);

        $response = $client->request('POST', 'sendPhoto', [
            'json' => [
                'method' => 'sendPhoto',

                'chat_id' => $request->input('callback_query.message.chat.id'),
                'photo' => 'https://tbots.categulario.tk/mountain.png',
                'caption' => 'The forecast',
            ],
        ]);
    }
}
