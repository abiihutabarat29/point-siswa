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
            <h5 class="card-title mb-2">{{ $menu }}</h5>
        @endif
        {{ $slot }}
    </div>
</div>
