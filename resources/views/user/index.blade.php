    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0 mb-3">
                <div class="dt-buttons">
                    <x-createBtn></x-createBtn>
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th>Nama</th>
                <th style="width:25%">Email</th>
                <th class="text-center" style="width:15%">Role</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-input type="text" name="name" label="Nama User" value="" opsi="true"></x-input>
            <x-input type="email" name="email" label="Email" value="" opsi="true"></x-input>
            <x-input type="password" name="password" label="Password" value="" opsi="true"></x-input>
            <x-input type="password" name="repassword" label="Re-Password" value="" opsi="true"></x-input>
            <div id="role-dropdown">
                <x-dropdown name="role_id" label="Role" opsi="true">
                    <option value="1">Admin</option>
                    <option value="2">Operator</option>
                    <option value="3">Guru</option>
                    <option value="4">Guru BK</option>
                    <option value="5">Kepala Sekolah</option>
                    <option value="6">Siswa</option>
                    <option value="7">Orang Tua</option>
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


                var myTable = DataTable("{{ route('manajemen-user.index') }}", [{
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
                var editUrl = "{{ route('manajemen-user.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['name', 'email', 'role_id'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('manajemen-user.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('manajemen-user.index') }}";
                var deleteUrl = "{{ route('manajemen-user.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
