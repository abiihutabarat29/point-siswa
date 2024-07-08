    @extends('layouts.app')
    @section('content')
        @if ($jadwalMapel->isNotEmpty())
            <div class="row">
                @foreach ($jadwalMapel as $item)
                    @if ($item->guru_mapel->mapel)
                        <div class="col-lg-4 col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="card-info">
                                            <p class="card-text">{{ $item->guru_mapel->mapel->name }}
                                                <span class="badge bg-label-secondary badge-sm">
                                                    {{ $item->start }} - {{ $item->end }}
                                                </span>
                                            </p>
                                            <div class="d-flex align-items-end mb-3">
                                                <h5 class="card-title mb-0 me-2">{{ $item->jadwal->rombel->name }}
                                                </h5>
                                                <small>[{{ $item->jadwal->rombel->siswa_rombel->count() }} orang]</small>
                                            </div>
                                        </div>
                                        <div class="card-icon text-center">
                                            @if (\Carbon\Carbon::now()->format('H:i:s') > $item->end)
                                                <span class="badge bg-label-success rounded p-2">
                                                    <i class='bx bx-check bx-xs'></i>
                                                </span>
                                                <div><small class="text-success">Selesai</small></div>
                                            @elseif (\Carbon\Carbon::now()->format('H:i:s') < $item->start)
                                                <span class="badge bg-label-warning rounded p-2">
                                                    <i class='bx bx-time bx-xs'></i>
                                                </span>
                                                <div><small class="text-warning p-2">Segera</small></div>
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <span class="badge bg-label-primary rounded p-2">
                                                        <i class='bx bx-circle bx-xs'></i>
                                                    </span>
                                                @else
                                                    <a href="{{ route('absensi.presensi', [Crypt::encrypt($item->jadwal->rombel->id), Crypt::encrypt($item->guru_mapel->mapel->id)]) }}"
                                                        type="button" class="btn btn-sm btn-primary">
                                                        <i class="bx bx-qr-scan"></i>
                                                    </a>
                                                @endif
                                                <div><small class="text-primary">Berlangsung</small></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="card bg-transparent border border-primary">
                <div class="card-body">
                    <div class="alert alert-warning text-center text-danger" role="alert">
                        Tidak ada jadwal hari ini...
                    </div>
                </div>
            </div>
        @endif
    @endsection
