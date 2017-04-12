<?php

namespace App\Jobs;

use GuzzleHttp\Client;

class GetForecast extends Job
{
    public $peak;

    public $height;

    public $chat_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($peak, $height, $chat_id)
    {
        $this->peak = $peak;
        $this->height = $height;
        $this->chat_id = $chat_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (app()->environment() == 'testing') {
            return;
        }

        $client = new Client([
            'base_uri' => 'https://api.telegram.org/bot'.config('mntnwttrbot.key').'/',
        ]);

        $response = $client->request('POST', 'sendPhoto', [
            'json' => [
                'method' => 'sendPhoto',

                'chat_id' => $this->chat_id,
                'photo'   => 'https://tbots.categulario.tk/mountain.png',
                'caption' => 'The forecast',
            ],
        ]);
    }
}
