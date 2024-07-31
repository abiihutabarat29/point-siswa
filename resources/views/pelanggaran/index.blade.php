    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                <div class="dt-action-buttons text-end pt-3 pt-md-0 mb-3">
                    <div class="dt-buttons">
                        <x-createBtn></x-createBtn>
                    </div>
                </div>
            @endif
            <x-table>
                <th style="width:5%">#</th>
                <th>Nama Pelanggaran</th>
                <th style="width:25%" class="text-center">Bobot</th>
                @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <th class="text-center" style="width:5%">Action</th>
                @endif
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-inputPlaceholder type="text" name="name" label="Nama Pelanggaran" placeholder="contoh: Bolos"
                opsi="true"></x-inputPlaceholder>
            <x-input type="number" name="bobot" label="Bobot" value="" opsi="true"></x-input>
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

                var myTable = DataTable("{{ route('pelanggaran.index') }}", [{
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
                        data: "bobot",
                        name: "bobot",
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
                var editUrl = "{{ route('pelanggaran.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['name', 'bobot'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('pelanggaran.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('pelanggaran.index') }}";
                var deleteUrl = "{{ route('pelanggaran.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
