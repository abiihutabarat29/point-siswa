<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/dashboard') }}" class="app-brand-link">
            <span class="menu-text fw-bold">PointApp</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <x-menu route="{{ route('dashboard') }}" name="dashboard" label="dashboard" icon="bx-home-circle"></x-menu>
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
            {{-- <li
                class="menu-item {{ request()->segment(1) == 'klasifikasi' ||
                request()->segment(1) == 'surat-masuk' ||
                request()->segment(1) == 'surat-keluar'
                    ? 'active open'
                    : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-envelope"></i>
                    <div data-i18n="Form Elements">Manajemen Surat</div>
                </a>
                <ul class="menu-sub">
                    <x-menuDropdown route="{{ route('klasifikasi.index') }}" name="klasifikasi" label="Klasifikasi">
                    </x-menuDropdown>
                    <x-menuDropdown route="{{ route('surat-masuk.index') }}" name="surat-masuk" label="Surat Masuk">
                    </x-menuDropdown>
                    <x-menuDropdown route="{{ route('surat-keluar.index') }}" name="surat-keluar"
                        label="Surat Keluar"></x-menuDropdown>
                </ul>
            </li> --}}
            {{-- <x-menu route="{{ route('jadwal.index') }}" name="jadwal" label="Jadwal" icon="bxs-calendar"></x-menu> --}}
            <x-menu route="{{ route('pelanggaran-siswa.index') }}" name="pelanggaran-siswa" label="Point Siswa"
                icon="bx-detail"></x-menu>
            <x-menu route="#" name="aktivitas-guru" label="Aktivitas Guru" icon="bx-calendar-check"></x-menu>
        @endif
        @if (auth()->user()->role_id == 3)
            <x-menu route="{{ route('point-pelanggaran-siswa') }}" name="point-pelanggaran-siswa"
                label="Pelanggaran Siswa" icon="bx-detail"></x-menu>
            <x-menu route="#" name="aktivitas-guru" label="Aktivitas Guru" icon="bx-calendar-check"></x-menu>
        @endif
        {{-- @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
            <x-menu route="{{ route('absensi.index') }}" name="absensi" label="Absensi"
                icon="bx-clipboard"></x-menu>
        @endif --}}

        {{-- @if (auth()->user()->role_id == 3)
            <x-menu route="#" name="jadwal" label="Jadwal" icon="bx-calendar"></x-menu>
            <x-menu route="#" name="laporan" label="Laporan Kehadiran" icon="bx-spreadsheet"></x-menu>
        @endif --}}

        {{-- @if (auth()->user()->role_id == 4)
            <x-menu route="#" name="jadwal" label="Jadwal" icon="bx-calendar"></x-menu>
            <x-menu route="#" name="laporan" label="Laporan Kehadiran" icon="bx-spreadsheet"></x-menu>
        @endif --}}
    </ul>
</aside>
