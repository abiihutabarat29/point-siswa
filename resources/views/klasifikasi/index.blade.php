    @extends('layouts.app')
    @section('content')
        <x-card>
            <x-createBtn></x-createBtn>
            <x-table>
                <th style="width:5%">#</th>
                <th style="width:20%">Kode Klasifikasi</th>
                <th>Nama Klasifikasi</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-inputPlaceholder type="text" name="kode" label="Kode Klasifikasi"
                placeholder="contoh: 01"></x-inputPlaceholder>
            <x-input type="text" name="name" label="Nama Klasifikasi" value=""></x-input>
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


                var myTable = DataTable("{{ route('klasifikasi.index') }}", [{
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
                        data: "name",
                        name: "name",
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
                var editUrl = "{{ route('klasifikasi.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['kode', 'name'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('klasifikasi.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('klasifikasi.index') }}";
                var deleteUrl = "{{ route('klasifikasi.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
