    @extends('layouts.app')
    @section('content')
        <x-card>
            <x-createBtn></x-createBtn>
            <x-table>
                <th style="width:5%">#</th>
                <th>NPSN</th>
                <th>Nama Sekolah</th>
                <th>Bentuk</th>
                <th>Status</th>
                <th>Kepala Sekolah</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-input type="text" name="name" label="name" value=""></x-input>
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


                var myTable = DataTable("{{ route('sekolah.index') }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "npsn",
                        name: "npsn",
                    },
                    {
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "bentuk",
                        name: "bentuk",
                    },
                    {
                        data: "status",
                        name: "status",
                    },
                    {
                        data: "kepsek",
                        name: "kepsek",
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
                var editUrl = "{{ route('sekolah.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['name', 'npsn', 'bentuk', 'status', 'kepsek'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('sekolah.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('sekolah.index') }}";
                var deleteUrl = "{{ route('sekolah.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
