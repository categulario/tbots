<?php

namespace App;

class Query
{
    public static function search($query)
    {
        return collect(
            json_decode(
                file_get_contents(__DIR__.'/../'.config('bots.mntnwttrbot.data'))
            )
        )->filter(function ($item) use ($query) {
            return $query ? strpos(strtolower($item->fqn), strtolower($query)) !== false : true;
        });
    }

    public static function find($peak)
    {
        return collect(
            json_decode(
                file_get_contents(__DIR__.'/../'.config('bots.mntnwttrbot.data'))
            )
        )->where('id', $peak)->first();
    }
}
