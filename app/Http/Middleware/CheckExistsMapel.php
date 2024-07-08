<?php

namespace App\Http\Middleware;

use App\Models\Mapel;
use Closure;

class CheckExistsMapel
{
    public function handle($request, Closure $next)
    {
        $activeMapel = Mapel::exists();

        if (!$activeMapel) {
            return redirect()->route('mapel.index')->with('error', 'Silahkan isi mata pelajaran terlebih dahulu');
        }

        return $next($request);
    }
}
