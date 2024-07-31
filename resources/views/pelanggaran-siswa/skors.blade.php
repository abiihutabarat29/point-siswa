    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons">
                    <x-button url="{{ route('pelanggaran-siswa.siswa', Crypt::encrypt($rombel->rombel->id)) }}"
                        label="Kembali" icon="bx-reply"></x-button>
                    @if (auth()->user()->role_id == 1 ||
                            auth()->user()->role_id == 2 ||
                            auth()->user()->role_id == 3 ||
                            auth()->user()->role_id == 4)
                        <x-createBtn></x-createBtn>
                        <x-exportBtn></x-exportBtn>
                    @endif
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th>Name</th>
                <th style="width:20%" class="text-center">Pelanggaran</th>
                <th style="width:15%" class="text-center">Poin</th>
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
    @endsection
    @section('script')
        <script text="javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                var myTable = DataTable("{{ url('/pelanggaran-siswa/skors', $id) }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "nama_siswa",
                        name: "nama_siswa",
                    },
                    {
                        data: "nama_pelanggaran",
                        name: "nama_pelanggaran",
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

                // Edit
                var editUrl = "{{ route('pelanggaran-siswa.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['siswa_id', 'pelanggaran_id'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('pelanggaran-siswa.store-siswa') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('pelanggaran-siswa.index') }}";
                var deleteUrl = "{{ route('pelanggaran-siswa.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
