    @extends('layouts.app')
    @section('content')
        <x-card>
            <x-createBtn></x-createBtn>
            <x-table>
                <th style="width:5%">#</th>
                <th>Nama Jurusan</th>
                <th>Singkatan</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-inputPlaceholder type="text" name="name" label="Name Jurusan"
                placeholder="contoh: Tehnik Komputer dan Jaringan"></x-inputPlaceholder>
            <x-inputPlaceholder type="text" name="short_name" label="Singkatan"
                placeholder="contoh: TKJ"></x-inputPlaceholder>
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


                var myTable = DataTable("{{ route('jurusan.index') }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "short_name",
                        name: "short_name",
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
                var editUrl = "{{ route('jurusan.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['name', 'short_name'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('jurusan.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('jurusan.index') }}";
                var deleteUrl = "{{ route('jurusan.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
