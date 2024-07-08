    @extends('layouts.app')
    @section('content')
        <x-card>
            <p>Pengampu Mata Pelajaran -
                @if (!$mapel)
                    <span class="badge bg-label-danger">
                        Belum ada mata pelajaran
                    </span>
                @else
                    {{ $mapel->name }}
                @endif
            </p>
            <hr>
            @if (!$guru)
                <span class="badge bg-label-danger">
                    Belum ada guru pengampu
                </span>
                <a href="{{ route('guru.index') }}" class="dt-button btn btn-sm rounded-pill btn-primary">
                    <span><i class="bx bx-plus me-1"></i>Tambah
                    </span>
                </a>
            @else
                <span class="badge bg-label-success">
                    {{ $guru->guru->name }}
                </span>
            @endif
        </x-card>
        <x-card>
            <x-table>
                <th style="width:5%">#</th>
                <th class="text-center" style="width:10%">Foto</th>
                <th style="width:15%">NISN</th>
                <th>Name</th>
                <th class="text-center" style="width:10%">Status</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-scan></x-scan>
    @endsection
    @section('script')
        <script text="javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                var myTable = DataTable(
                    "{{ route('absensi.presensi', [Crypt::encrypt($rombel->id), Crypt::encrypt($mapel->id)]) }}",
                    [{
                            "data": null,
                            "orderable": false,
                            "searchable": false,
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: "foto",
                            name: "foto",
                        },
                        {
                            data: "nisn",
                            name: "nisn",
                        },
                        {
                            data: "name",
                            name: "name",
                        },
                        {
                            data: "status",
                            name: "status",
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false,
                        }
                    ]);

                // Scan Absen
                var scanUrl = "{{ route('absensi.scan') }}";
                Scan(scanUrl, myTable)
            });
        </script>
    @endsection
