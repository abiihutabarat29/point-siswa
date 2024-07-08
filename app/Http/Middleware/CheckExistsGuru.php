<?php

namespace App\Http\Middleware;

use App\Models\Guru;
use Closure;

class CheckExistsGuru
{
    public function handle($request, Closure $next)
    {
        $activeMapel = Guru::exists();

        if (!$activeMapel) {
            return redirect()->route('guru.index')->with('error', 'Silahkan isi guru terlebih dahulu');
        }

        return $next($request);
    }
}
