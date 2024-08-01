<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mb-3">
        <img src="{{ asset('assets/img/favicon/smk-n-air-putih.png') }}" alt="SMK Negeri Air Putih"
            style="width: 50px; height: 50px;" class="me-3">
        <a href="{{ url('/dashboard') }}" class="app-brand-link d-flex align-items-center">
            <span class="menu-text fw-bold">SMKN 1 Air Putih</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <div class="d-flex justify-content-center align-items-center mb-2">
        <span class="badge bg-label-secondary me-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
        <span class="badge bg-label-secondary" id="jam"></span>
    </div>
    <ul class="menu-inner py-1">
        <x-menu route="{{ route('dashboard') }}" name="dashboard" label="dashboard" icon="bx-home-circle"></x-menu>
        <li class="menu-header small text-uppercase m-0">
            <span class="menu-header-text">MENU</span>
        </li>
        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
            <li
                class="menu-item {{ request()->segment(1) == 'tapel' ||
                request()->segment(1) == 'mapel' ||
                request()->segment(1) == 'jurusan' ||
                request()->segment(1) == 'kelas' ||
                request()->segment(1) == 'rombel' ||
                request()->segment(1) == 'guru' ||
                request()->segment(1) == 'siswa' ||
                request()->segment(1) == 'pelanggaran' ||
                request()->segment(1) == 'manajemen-user'
                    ? 'active open'
                    : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-server"></i>
                    <div data-i18n="Form Elements">Master Data</div>
                </a>
                <ul class="menu-sub">
                    <x-menuDropdown route="{{ route('tapel.index') }}" name="tapel" label="Tahun Pelajaran">
                    </x-menuDropdown>
                    {{-- <x-menuDropdown route="{{ route('mapel.index') }}" name="mapel"
                        label="Mata Pelajaran"></x-menuDropdown> --}}
                    <x-menuDropdown route="{{ route('jurusan.index') }}" name="jurusan"
                        label="Jurusan"></x-menuDropdown>
                    <x-menuDropdown route="{{ route('kelas.index') }}" name="kelas" label="Kelas"></x-menuDropdown>
                    <x-menuDropdown route="{{ route('rombel.index') }}" name="rombel"
                        label="Kelas/Rombel"></x-menuDropdown>
                    <x-menuDropdown route="{{ route('guru.index') }}" name="guru" label="Guru"></x-menuDropdown>
                    <x-menuDropdown route="{{ route('siswa.index') }}" name="siswa" label="Siswa"></x-menuDropdown>
                    <x-menuDropdown route="{{ route('pelanggaran.index') }}" name="pelanggaran"
                        label="Pelanggaran"></x-menuDropdown>
                    <x-menuDropdown route="{{ route('manajemen-user.index') }}" name="manajemen-user"
                        label="Manajemen User"></x-menuDropdown>
                </ul>
            </li>
        @endif
        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 5)
            <x-menu route="{{ route('pelanggaran-siswa.index') }}" name="pelanggaran-siswa" label="Point Siswa"
                icon="bx-detail"></x-menu>
            {{-- <x-menu route="#" name="aktivitas-guru" label="Aktivitas Guru" icon="bx-calendar-check"></x-menu> --}}
        @endif
        @if (auth()->user()->role_id == 3 || auth()->user()->role_id == 4)
            <x-menu route="{{ route('point-pelanggaran-siswa') }}" name="point-pelanggaran-siswa"
                label="Pelanggaran Siswa" icon="bx-detail"></x-menu>
            {{-- <x-menu route="#" name="aktivitas-guru" label="Aktivitas Guru" icon="bx-calendar-check"></x-menu> --}}
        @endif
    </ul>
</aside>
