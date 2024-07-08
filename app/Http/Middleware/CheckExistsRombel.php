<?php

namespace App\Http\Middleware;

use App\Models\Rombel;
use Closure;

class CheckExistsRombel
{
    public function handle($request, Closure $next)
    {
        $activeMapel = Rombel::exists();

        if (!$activeMapel) {
            return redirect()->route('rombel.index')->with('error', 'Silahkan isi rombongan belajar terlebih dahulu');
        }

        return $next($request);
    }
}
