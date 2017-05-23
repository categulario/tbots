<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use App\Bots\BotManager;

class ChooseBotMiddleware
{
    public function __construct(BotManager $bots)
    {
        $this->bm = $bots;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $botname = $request->path();

        if (!$this->bm->exist($botname)) {
            return response()->json([
                'msg' => 'bot not found',
            ], 404);
        }

        $request->bot = $this->bm->getBot($botname);

        return $next($request);
    }
}
