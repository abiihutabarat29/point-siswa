    @extends('layouts.app')
    @section('content')
        <div class="row g-2">
            @include('tapel.tapel')
            @include('tapel.semester')
        </div>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-inputPlaceHolder type="text" name="tahun" label="Tahun" placeholder="contoh: 2023/2024">
            </x-inputPlaceHolder>
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

                var myTable = DataTable("{{ route('tapel.index') }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "tahun",
                        name: "tahun",
                    },
                    {
                        data: "status",
                        name: "status",
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
                var editUrl = "{{ route('tapel.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['tahun', 'semester'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('tapel.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('tapel.index') }}";
                var deleteUrl = "{{ route('tapel.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });

            $(document).ready(function() {
                @if ($message = Session::get('success'))
                    var message = "{{ $message }}"
                    alertToastr(message);
                @endif
            });
        </script>
    @endsection
