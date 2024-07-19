<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruMapelController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\MapelKelompokController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PelanggaranSiswaController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\SiswaAbsensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaRombelController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TapelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false, // Register Routes...
    'reset' => false, // Reset Password Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/', [LoginController::class, 'showLoginForm']);

// Admin
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::resource('/manajemen-user', UserController::class);
    Route::resource('/sekolah', SekolahController::class);
});

// Admin & Operator
Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/siswa/{kelas_id}', [SiswaController::class, 'get']);
    Route::get('/siswa/rombel/{id}', [SiswaRombelController::class, 'index'])->name('siswa.rombel');
    Route::get('/tapel/status/{id}', [TapelController::class, 'status'])->name('tapel.status');
    Route::get('/tapel/statussem/{id}', [TapelController::class, 'statusSem'])->name('tapel.statussem');
    Route::post('/tapel/he', [TapelController::class, 'storeHE'])->name('tapel.he');
    Route::get('/rombel/get-data', [RombelController::class, 'getData'])->name('rombel.get');
    Route::resource('/tapel', TapelController::class);
    Route::resource('/klasifikasi', KlasifikasiController::class);
    Route::resource('/surat-masuk', SuratMasukController::class);
    Route::get('surat-masuk/download/{id}', [SuratMasukController::class, 'download']);
    Route::get('surat-masuk/review/{id}', [SuratMasukController::class, 'review']);
    Route::resource('/surat-keluar', SuratKeluarController::class);
    Route::get('surat-keluar/download/{id}', [SuratKeluarController::class, 'download']);
    Route::get('surat-keluar/review/{id}', [SuratKeluarController::class, 'review']);
    Route::resource('/jurusan', JurusanController::class);
    Route::resource('/kelas', KelasController::class);
    Route::resource('/siswa', SiswaController::class);

    Route::group(['middleware' => 'exists.mapel'], function () {
        Route::post('/guru/import/', [GuruController::class, 'import'])->name('guru.import');
        Route::get('/guru/jabatan/{id}', [GuruController::class, 'jabatanGet'])->name('guru.jabatan');
        Route::post('/guru/jabatan/{id}', [GuruController::class, 'jabatanUpdate'])->name('guru.jabatan');
        Route::resource('/guru', GuruController::class);
        Route::get('/guru/mapel/{id}', [GuruMapelController::class, 'index'])->name('guru.mapel');
    });

    Route::group(['middleware' => 'exists.jurusan'], function () {
        Route::resource('/mapel', MapelController::class);
        Route::post('mapel-import', [MapelController::class, 'import'])->name('mapel.import');
        Route::get('/mapel-kelompok', [MapelKelompokController::class, 'index']);
        Route::post('/mapel-kelompok', [MapelKelompokController::class, 'storeKel'])->name('mapelKelompok.store');
        Route::get('/mapel-kelompok/{id}/edit', [MapelKelompokController::class, 'edit'])->name('mapelKelompok.edit');
        Route::delete('/mapel-kelompok/{id}', [MapelKelompokController::class, 'destroy']);
        Route::get('/mapel-subkelompok', [MapelKelompokController::class, 'subKelompok']);
        Route::post('/mapel-subkelompok', [MapelKelompokController::class, 'storeSub'])->name('mapelSubKelompok.store');
    });

    Route::group(['middleware' => 'active.tapel'], function () {
        Route::group(['middleware' => 'exists.kelas'], function () {
            Route::resource('/rombel', RombelController::class);
        });
    });

    Route::get('/rombel/siswa/{id}', [RombelController::class, 'siswa'])->name('rombel.siswa');
    Route::get('/rombel/jadwal/{id}', [RombelController::class, 'jadwal'])->name('rombel.jadwal');

    Route::resource('/pelanggaran', PelanggaranController::class);
    Route::resource('/pelanggaran-siswa', PelanggaranSiswaController::class);
    Route::get('/pelanggaran-siswa/siswa/{id}', [PelanggaranSiswaController::class, 'siswa'])
        ->name('pelanggaran-siswa.siswa');
    Route::get('/pelanggaran-siswa/skors/{id}', [PelanggaranSiswaController::class, 'skors'])
        ->name('pelanggaran-siswa.skors');
    Route::post('/pelanggaran-siswa/skors/store-siswa', [PelanggaranSiswaController::class, 'storeSiswa'])
        ->name('pelanggaran-siswa.store-siswa');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/rombel/{id}', [JadwalController::class, 'rombel'])->name('jadwal.rombel');
    Route::post('/jadwal/rombel/{id}', [JadwalController::class, 'store'])->name('jadwal.rombel');
    Route::post('/jadwal/mapel/{id}', [JadwalController::class, 'storeMapel'])->name('jadwal.mapel');

    Route::get('/absensi', [SiswaAbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/presensi/{id}/{idm}', [SiswaAbsensiController::class, 'presensi'])->name('absensi.presensi');
    Route::post('absensi/scan', [SiswaAbsensiController::class, 'scanAbsensi'])->name('absensi.scan');
});

// Admin, Operator, Guru
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

//Guru
Route::middleware(['auth', 'role:1,3'])->group(function () {

    Route::get('/absensi', [SiswaAbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/presensi/{id}/{idm}', [SiswaAbsensiController::class, 'presensi'])->name('absensi.presensi');
    Route::post('absensi/scan', [SiswaAbsensiController::class, 'scanAbsensi'])->name('absensi.scan');

    Route::get('/siswa/rombel/{id}', [SiswaRombelController::class, 'index'])->name('siswa.rombel');
    Route::resource('/point-siswa', PelanggaranSiswaController::class);
    Route::get('/point-pelanggaran-siswa', [PelanggaranSiswaController::class, 'pointSiswa'])
        ->name('point-pelanggaran-siswa');
    Route::post('/point-pelanggaran-siswa/store', [PelanggaranSiswaController::class, 'storePoint'])
        ->name('point-pelanggaran-siswa.store');
});

// Siswa
Route::middleware(['auth', 'role:4'])->group(function () {
});
