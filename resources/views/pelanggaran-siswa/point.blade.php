    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons">
                    <x-button url="{{ route('pelanggaran-siswa.index') }}" label="Kembali"></x-button>
                    <x-createBtn></x-createBtn>
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th class="text-center" style="width:10%">Foto</th>
                <th style="width:10%">NISN</th>
                <th>Name</th>
                <th style="width:20%">Gender</th>
                <th style="width:10%" class="text-center">Poin</th>
                <th style="width:10%" class="text-center">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-dropdown name="rombel_id" label="Rombel">
                @foreach ($rombel as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </x-dropdown>
            <x-dropdown name="siswa_id" label="Siswa"></x-dropdown>
            <x-dropdown name="pelanggaran_id" label="Pelanggaran">
                @foreach ($pelanggaran as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </x-dropdown>
            <x-textarea name="keterangan" label="Keterangan" opsi="false"></x-textarea>
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

                var myTable = DataTable("{{ route('point-pelanggaran-siswa') }}", [{
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "foto",
                        name: "foto",
                    },
                    {
                        data: "nisn",
                        name: "nisn",
                    },
                    {
                        data: "siswa",
                        name: "siswa",
                    },
                    {
                        data: "gender",
                        name: "gender",
                    },
                    {
                        data: "poin",
                        name: "poin",
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

                // Save
                saveBtn("{{ route('point-pelanggaran-siswa.store') }}", myTable);

                // Edit
                var editUrl = "{{ route('point-siswa.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['rombel', 'siswa_id', 'pelanggaran_id', 'keterangan'];
                editModel(editUrl, editHeading, field)

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('point-siswa.index') }}";
                var deleteUrl = "{{ route('point-pelanggaran-siswa.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)

                // Fetch siswa based on rombel
                $('select[name="rombel_id"]').on('change', function() {
                    var rombel_id = $(this).val();
                    if (rombel_id) {
                        $.ajax({
                            url: "{{ route('siswa.rombel', '') }}/" + rombel_id,
                            type: "GET",
                            success: function(data) {
                                $('select[name="siswa_id"]').empty();
                                if (data.length === 0) {
                                    $('select[name="siswa_id"]').html(
                                        '<option value="">--Data Siswa Kosong--</option>');
                                } else {
                                    $('select[name="siswa_id"]').append(
                                        '<option value="">::Pilih Siswa::</option>');
                                    $.each(data, function(key, value) {
                                        $('select[name="siswa_id"]').append(
                                            '<option value="' + value.siswa.id + '">' +
                                            value.siswa.name + '</option>');
                                    });
                                }
                            },
                            error: function() {
                                $('select[name="siswa_id"]').empty();
                                $('select[name="siswa_id"]').html(
                                    '<option value="">Terjadi kesalahan, silakan coba lagi.</option>'
                                );
                            }
                        });
                    } else {
                        $('select[name="siswa_id"]').empty();
                    }
                });
            });
        </script>
    @endsection
