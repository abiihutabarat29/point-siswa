    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons">
                        <x-button-right url="{{ route('rombel.create') }}" label="Rombel Baru" icon="bx-plus">
                        </x-button-right>
                    </div>
                </div>
            @endif

            <x-table>
                <th style="width:5%">#</th>
                <th style="width:10%">Rombel</th>
                <th>Jurusan</th>
                <th style="width:25%">Wali</th>
                <th style="width:8%" class="text-center">Jlh Siswa</th>
                <th style="width:10%" class="text-center">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
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

                var myTable = DataTable("{{ route('rombel.index') }}", [{
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
                        data: "jurusan",
                        name: "jurusan",
                    },
                    {
                        data: "guru",
                        name: "guru",
                    },
                    {
                        data: "jlh_siswa",
                        name: "jlh_siswa",
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    }
                ]);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('rombel.index') }}";
                var deleteUrl = "{{ route('rombel.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)
            });
        </script>
    @endsection
