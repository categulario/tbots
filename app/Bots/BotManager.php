<?php

namespace App\Bots;

use Log;

class BotManager
{
    private $bots = [];

    public function __construct()
    {
        foreach (config('bots.list') as $botclass) {
            $botname = strtolower(explode('\\', $botclass)[2]);
            $bot = new $botclass(config("bots.$botname.token"));

            Log::debug($botname);
            Log::debug($bot->token);

            $this->bots[$bot->token] = $bot;
        }
    }

    public function exist($botname)
    {
        return in_array($botname, array_keys($this->bots));
    }

    public function getBot($botname)
    {
        return $this->bots[$botname];
    }
}
