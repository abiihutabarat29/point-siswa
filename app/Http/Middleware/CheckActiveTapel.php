<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tapel;

class CheckActiveTapel
{
    public function handle($request, Closure $next)
    {
        $activeTapel = Tapel::where('status', 1)->first();

        if (!$activeTapel) {
            return redirect()->route('tapel.index')->with('error', 'Silahkan isi atau aktifkan Tahun Ajaran terlebih dahulu');
        }

        return $next($request);
    }
}
