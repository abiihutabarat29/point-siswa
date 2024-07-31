    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons">
                    <x-button url="{{ route('point-pelanggaran-siswa') }}" label="Kembali" icon="bx-reply"></x-button>
                    {{-- <x-exportBtn></x-exportBtn> --}}
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th>Name</th>
                <th style="width:20%" class="text-center">Pelanggaran</th>
                <th style="width:20%">Pelapor</th>
                <th style="width:10%" class="text-center">Poin</th>
                <th style="width:10%" class="text-center">Status Verifikasi</th>
                <th style="width:10%" class="text-center">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <input type="hidden" name="siswa_id" value="{{ $siswa->id }}" opsi="true">
            <x-dropdown name="pelanggaran_id" label="Pelanggaran" opsi="true">
                @foreach ($pelanggaran as $pelang)
                    <option value="{{ $pelang->id }}">{{ $pelang->name }} (Point {{ $pelang->bobot }})</option>
                @endforeach
            </x-dropdown>
        </x-offcanvas>
        <x-delete></x-delete>
        <x-konfirmasi></x-konfirmasi>
        <x-tolak>
            <x-textarea name="alasan" class="form-control" id="alasan" rows="3" label="Berikan Alasan Penolakan"
                opsi="true"></x-textarea>
        </x-tolak>
        <x-modalDetail>
            <div id="detail"></div>
        </x-modalDetail>
    @endsection
    @section('script')
        <script text="javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                var myTable = DataTable("{{ route('point-pelanggaran-siswa.riwayat', $id) }}", [{
                        "data": null,
                        orderable: false,
                        searchable: false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "nama_siswa",
                        name: "nama_siswa",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "nama_pelanggaran",
                        name: "nama_pelanggaran",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "pelapor",
                        name: "pelapor",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "poin",
                        name: "poin",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "status",
                        name: "status",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    }
                ]);

                // Create
                // var createHeading = "Tambah {{ $menu }}";
                // createModel(createHeading)

                // Edit
                // var editUrl = "{{ route('pelanggaran-siswa.index') }}";
                // var editHeading = "Edit {{ $menu }}";
                // var field = ['siswa_id', 'pelanggaran_id'];
                // editModel(editUrl, editHeading, field)

                // Save
                // saveBtn("{{ route('pelanggaran-siswa.store-siswa') }}", myTable);

                // Delete
                // var fitur = "{{ $menu }}";
                // var editUrl = "{{ route('point-siswa.index') }}";
                // var deleteUrl = "{{ route('point-siswa.store') }}";
                // Delete(fitur, editUrl, deleteUrl, myTable)

                // Detail Point
                var urlDetail = "{{ route('point-siswa.index') }}";
                var path = "{{ asset('storage/foto-pelanggaran') }}";
                var detailHeading = "Detail Pelanggaran";
                Detail(urlDetail, path, detailHeading)

                // Konfirmasi
                var url = "{{ route('konfirmasi.skor') }}";
                konfirmasiSkor(url, myTable)

                // Tolak
                var url = "{{ route('tolak.skor') }}";
                tolakSkor(url, myTable)
            });
        </script>
    @endsection
