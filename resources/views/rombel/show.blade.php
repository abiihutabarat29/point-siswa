@extends('layouts.app')
@section('content')
    <div class="px-2">
        <a href="{{ route('rombel.index') }}" type="button" class="btn btn-outline-danger mb-3">
            <span class="tf-icons bx bx-reply-all"></span>Kembali
        </a>
    </div>
    <div class="row g-0">
        <div class="col mb-0 px-2">
            <x-card>
                <h6>Siswa Kelas {{ $data->name }}</h6>
                <div class="card-datatable table-responsive">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>N I S N</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($siswa as $sis)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">{{ $sis->siswa->nisn }}</td>
                                        <td>{{ $sis->siswa->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>
        </div>
        <div class="col mb-0 px-2">
            <x-card>
                <h6>Detail Kelas {{ $data->name }}</h6>
                <div class="card-datatable table-responsive">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td style="width:30%">Nama Kelas</td>
                                    <td>{{ $data->name }}</td>
                                </tr>
                                <tr>
                                    <td>Jurusan</td>
                                    <td>{{ $data->jurusan->name }}</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>{{ $data->kelas->name }}</td>
                                </tr>
                                <tr>
                                    <td>Wali Kelas</td>
                                    <td>{{ $data->guru->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah Siswa</td>
                                    <td>{{ $data->siswa_rombel->count() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="hr">

                <h6>Struktur Organisasi Kelas {{ $data->name }}</h6>
                <div class="card-datatable table-responsive">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Jabatan</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Wali Kelas</td>
                                    <td>{{ $data->guru->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Ketua Kelas</td>
                                    <td>{{ $data->siswa->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Wakil Ketua</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td>Bendahara</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
