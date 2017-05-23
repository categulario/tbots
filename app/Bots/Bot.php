<?php

namespace App\Bots;

use Illuminate\Http\Request;

class Bot
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function hello(Request $request)
    {
        return ['msg' => "I'm alive!"];
    }
}
