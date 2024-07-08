<?php

namespace App\Http\Middleware;

use App\Models\Jurusan;
use Closure;

class CheckExistsJurusan
{
    public function handle($request, Closure $next)
    {
        $activeMapel = Jurusan::exists();

        if (!$activeMapel) {
            return redirect()->route('jurusan.index')->with('error', 'Silahkan isi jurusan terlebih dahulu');
        }

        return $next($request);
    }
}
