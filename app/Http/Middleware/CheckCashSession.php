<?php

namespace App\Http\Middleware;

use App\Models\CashSession;
use Closure;
use Illuminate\Http\Request;

class CheckCashSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $cashSession = CashSession::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('open', true)->exists();
        if (!$cashSession) {
            $path = explode('/', $request->path());
            return redirect('/' . $path[0]);
        }

        return $next($request);
    }
}
