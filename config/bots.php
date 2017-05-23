<?php

return [

    'list' => [
        App\Bots\Mntnwttrbot::class,
        App\Bots\Eqxbot::class,
    ],

    'mntnwttrbot' => [

        'token' => env('MNTNWTTRBOT_TOKEN', 'mntnwttrtoken'),
        'data' => env('MNTN_DATA', 'resources/datamining/mountains.json'),
        'phantomjs' => env('PHANTOMJS', '/usr/bin/phantomjs'),

    ],

    'eqxbot' => [

        'token' => env('EQXBOT_TOKEN', 'eqxtoken'),

    ],

];
