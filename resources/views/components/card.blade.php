@props(['menu'])

<div class="card col mb-4">
    <div class="card-body">
        @if (
            !request()->routeIs('rombel.create') &&
                !request()->routeIs('rombel.edit') &&
                !request()->routeIs('rombel.show') &&
                !request()->routeIs('guru.jabatan') &&
                !request()->routeIs('guru.show') &&
                !request()->routeIs('tapel.index'))
            @if (request()->routeIs('pelanggaran-siswa.skors'))
                <span class="badge bg-primary">{{ $menu }}</span>
            @else
                <h5 class="card-title mb-2">{{ $menu }}</h5>
            @endif
        @endif
        {{ $slot }}
    </div>
</div>
