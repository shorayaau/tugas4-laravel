<?php

// app/Http/Middleware/Fail2Ban.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class Fail2Ban
{
    public function handle($request, Closure $next)
    {
        $ipAddress = Request::ip();
        $failCount = Cache::get("fail2ban:$ipAddress", 0);

        if ($failCount >= 3) {
            $banTime = Cache::get("fail2ban:$ipAddress:ban_time", 0);

            if (time() < $banTime) {
                $remainingTime = $banTime - time();
                return redirect()->back()
                    ->withErrors(['fail2ban' => "Anda telah gagal login 3 kali, silakan coba lagi dalam $remainingTime detik"]);
            }

            Cache::forget("fail2ban:$ipAddress");
            Cache::forget("fail2ban:$ipAddress:ban_time");
        }

        $response = $next($request);

        if ($response->status() === 302 && !$request->session()->has('authenticated')) {
            $failCount += 1;
            Cache::put("fail2ban:$ipAddress", $failCount, now()->addMinutes(1));

            if ($failCount >= 3) {
                $banTime = time() + 30;
                Cache::put("fail2ban:$ipAddress:ban_time", $banTime, $banTime);
            }
        }

        return $response;
    }
}
