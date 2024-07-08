    @extends('layouts.app')
    @section('content')
        <x-card>
            <x-createBtn></x-createBtn>
            <x-table>
                <th style="width:5%">#</th>
                <th>ID Card</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-input type="text" name="name" label="Nama User" value=""></x-input>
            <x-input type="email" name="email" label="Email" value=""></x-input>
            <x-input type="password" name="password" label="Password" value=""></x-input>
            <x-input type="password" name="repassword" label="Re-Password" value=""></x-input>
            <div id="role-dropdown">
                <x-dropdown name="role_id" label="Role">
                    <option value="1">Admin</option>
                    <option value="2">Operator</option>
                </x-dropdown>
            </div>
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


                var myTable = DataTable("{{ route('user.index') }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "id_card",
                        name: "id_card",
                    },
                    {
                        data: "name",
                        name: "name",
                    },
                    {
                        data: "email",
                        name: "email",
                    },
                    {
                        data: "role",
                        name: "role",
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
                var editUrl = "{{ route('user.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['name', 'email'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('user.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('user.index') }}";
                var deleteUrl = "{{ route('user.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
