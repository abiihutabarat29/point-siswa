    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons">
                    <x-importBtn></x-importBtn>
                    <x-createBtn></x-createBtn>
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th class="text-center" style="width:10%">Foto</th>
                <th>NISN</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Tgl Lahir</th>
                <th>QR</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-delete></x-delete>
        <x-modal size="modal-lg">
            <div class="row g-2">
                <div class="col mb-0">
                    <x-input type="text" name="nisn" label="NISN" value="" opsi="true"></x-input>
                    <x-dropdown name="gender" label="Jenis Kelamin" opsi="true">
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </x-dropdown>
                    <x-dropdown name="pendaftaran" label="Pendaftaran" opsi="true">
                        <option value="Siswa Baru">Siswa Baru</option>
                        <option value="Pindahan">Pindahan</option>
                    </x-dropdown>
                    <x-input type="date" name="tgl_masuk" label="Tanggal Masuk" value="" opsi="true"></x-input>
                </div>
                <div class="col mb-0">
                    <x-input type="text" name="name" label="Nama" value="" opsi="true"></x-input>
                    <x-input type="text" name="tmp_lahir" label="Tempat Lahir" value="" opsi="true"></x-input>
                    <x-input type="date" name="tgl_lahir" label="Tanggal Lahir" value="" opsi="true"></x-input>
                    <x-image type="file" name="foto" label="Foto" value="" opsi="true"></x-image>
                </div>
            </div>
        </x-modal>
    @endsection
    @section('script')
        <script text="javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                var myTable = DataTable("{{ route('siswa.index') }}", [{
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
                        data: "gender",
                        name: "gender",
                    },
                    {
                        data: "tgl_lahir",
                        name: "tgl_lahir",
                    },
                    {
                        data: "qr",
                        name: "qr",
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
                var editUrl = "{{ route('siswa.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['name', 'nisn', 'gender', 'tmp_lahir', 'tgl_lahir', 'photo', 'pendaftaran',
                    'tgl_masuk'
                ];
                editModel(editUrl, editHeading, field)

                // Save
                saveImage("{{ route('siswa.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('siswa.index') }}";
                var deleteUrl = "{{ route('siswa.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)

                Image("foto")
            });
        </script>
    @endsection
