    @extends('layouts.app')
    @section('content')
        <x-card>
            <x-createBtn></x-createBtn>
            <x-table>
                <th style="width:5%">#</th>
                <th style="width:8%">Kode</th>
                <th style="width:15%">Nomor Surat</th>
                <th>Perihal</th>
                <th>Asal Surat</th>
                <th style="width:10%">Tanggal Surat</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-delete></x-delete>
        <x-review></x-review>
        <x-modal size="modal-lg">
            <div class="row g-3">
                <div class="col mb-0">
                    <x-input type="text" name="nomor" label="Nomor Surat" value=""></x-input>
                    <x-dropdown name="sifat" label="Sifat Surat">
                        <option value="Biasa">Biasa</option>
                        <option value="Penting">Penting</option>
                        <option value="Segera">Segera</option>
                        <option value="Sangat Biasa">Sangat Biasa</option>
                        <option value="Amat Segera">Amat Segera</option>
                        <option value="Rahasia">Rahasia</option>
                    </x-dropdown>
                    <x-textarea name="perihal" label="Perihal"></x-textarea>
                    <x-input type="text" name="asal" label="Asal Surat" value=""></x-input>
                </div>
                <div class="col mb-0">
                    <x-dropdown name="klasifikasi_id" label="Klasifikasi Surat">
                        @foreach ($klasifikasi as $data)
                            <option value="{{ $data->id }}">({{ $data->kode }}) {{ $data->name }}</option>
                        @endforeach
                    </x-dropdown>
                    <x-input type="date" name="tgl_surat" label="Tanggal Surat" value=""></x-input>
                    <div id="fileSection" style="display: none;">
                        <hr>
                        <label for="file">File Lama</label>
                        <a id="fileLink" href="#" target="_blank"></a>
                        <hr>
                    </div>
                    <x-file type="file" name="file_surat" label="Upload File" value=""></x-file>
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

                var myTable = DataTable("{{ route('surat-masuk.index') }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "kode",
                        name: "kode",
                    },
                    {
                        data: "nomor",
                        name: "nomor",
                    },
                    {
                        data: "perihal",
                        name: "perihal",
                    },
                    {
                        data: "asal",
                        name: "asal",
                    },
                    {
                        data: "tgl_surat",
                        name: "tgl_surat",
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
                var editUrl = "{{ route('surat-masuk.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['klasifikasi_id', 'nomor', 'sifat', 'perihal', 'asal', 'tgl_surat'];
                editModel(editUrl, editHeading, field)

                // Save
                saveImage("{{ route('surat-masuk.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('surat-masuk.index') }}";
                var deleteUrl = "{{ route('surat-masuk.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)

                var dokumenUrl = "{{ url('surat-masuk/review') }}";
                var dokumenPath = "{{ asset('storage/surat-masuk') }}";
                Review(dokumenUrl, dokumenPath);
            });
        </script>
    @endsection
