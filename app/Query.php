<?php

namespace App;

class Query
{
    public static function search($query)
    {
        return collect(
            json_decode(
                file_get_contents(__DIR__.'/../'.config('mntnwttrbot.data'))
            )
        )->filter(function ($item) use ($query) {
            return $query ? strpos(strtolower($item->fqn), strtolower($query)) !== false : true;
        });
    }
}