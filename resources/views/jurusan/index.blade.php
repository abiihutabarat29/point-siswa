    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons">
                    <x-createBtn></x-createBtn>
                </div>
            </div>
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
            <x-inputPlaceHolder type="text" name="name" label="Name Jurusan"
                placeholder="contoh: Tehnik Komputer dan Jaringan"></x-inputPlaceHolder>
            <x-inputPlaceHolder type="text" name="short_name" label="Singkatan"
                placeholder="contoh: TKJ"></x-inputPlaceHolder>
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
