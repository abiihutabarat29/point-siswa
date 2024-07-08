    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons">
                    <x-button url="{{ route('pelanggaran-siswa.index') }}" label="Kembali"></x-button>
                    <x-createBtn></x-createBtn>
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th class="text-center" style="width:10%">Foto</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Poin</th>
                <th style="width:15%" class="text-center">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-dropdown name="siswa_id" label="Siswa">
                @foreach ($siswaRombel as $sis)
                    <option value="{{ $sis->siswa->id }}">{{ $sis->siswa->name }}</option>
                @endforeach
            </x-dropdown>
            <x-dropdown name="pelanggaran_id" label="Pelanggaran">
                @foreach ($pelanggaran as $pelang)
                    <option value="{{ $pelang->id }}">{{ $pelang->name }}</option>
                @endforeach
            </x-dropdown>
        </x-offcanvas>
        <x-delete></x-delete>
    @endsection
    @section('script')
        <script text="javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                var myTable = DataTable("{{ url('/pelanggaran-siswa/siswa', $id) }}", [{
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
                        data: "siswa",
                        name: "siswa",
                    },
                    {
                        data: "gender",
                        name: "gender",
                    },
                    {
                        data: "poin",
                        name: "poin",
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    }
                ]);

                // Create
                var createHeading = "Tambah {{ $menu }}";
                createModel(createHeading)

                // Save
                saveBtn("{{ route('pelanggaran-siswa.store') }}", myTable);
            });
        </script>
    @endsection
