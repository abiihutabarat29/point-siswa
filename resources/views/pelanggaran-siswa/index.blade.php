    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <x-table>
                <th style="width:5%">#</th>
                <th style="width:15%">Tahun Pelajaran</th>
                <th style="width:10%">Rombel</th>
                <th style="width:15%">Jurusan</th>
                <th>Wali</th>
                <th style="width:10%" class="text-center">Jlh Siswa</th>
                <th style="width:15%" class="text-center">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('script')
        <script text="javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                var myTable = DataTable("{{ route('pelanggaran-siswa.index') }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "tapel",
                        name: "tapel",
                    },
                    {
                        data: "rombel",
                        name: "rombel",
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

            });
        </script>
    @endsection
