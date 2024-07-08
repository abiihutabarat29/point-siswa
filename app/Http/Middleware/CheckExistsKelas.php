<?php

namespace App\Http\Middleware;

use App\Models\Kelas;
use Closure;

class CheckExistsKelas
{
    public function handle($request, Closure $next)
    {
        $activeMapel = Kelas::exists();

        if (!$activeMapel) {
            return redirect()->route('kelas.index')->with('error', 'Silahkan isi kelas terlebih dahulu');
        }

        return $next($request);
    }
}
