<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Log;

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

        $output = shell_exec(implode(' ', [
            '/home/categulario/.local/bin/phantomjs',
            base_path().'/resources/capture/capture.js',
            $this->peak,
            $this->height,
            base_path().'/public/forecasts/',
        ]));

        $filename = trim($output);

        $client = new Client([
            'base_uri' => 'https://api.telegram.org/bot'.config('mntnwttrbot.key').'/',
        ]);

        try {
            $photo_url = 'https://tbots.categulario.tk/forecasts/'.$filename.'?'.time();

            $response = $client->request('POST', 'sendPhoto', [
                'json' => [
                    'method' => 'sendPhoto',

                    'chat_id' => $this->chat_id,
                    'photo'   => $photo_url,
                    'caption' => 'The forecast',
                ],
            ]);
        } catch (ClientException $e) {
            Log::debug($e);
        }
    }
}
