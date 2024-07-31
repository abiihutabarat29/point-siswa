    @extends('layouts.app')
    @section('content')
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class="dt-buttons">
                    <button class="dt-button btn btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button"
                        id="create">
                        <span>
                            <span class="d-lg-inline-block">LAPORKAN</span>
                        </span>
                    </button>
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th class="text-center" style="width:10%">Foto</th>
                <th style="width:10%">Rombel</th>
                <th style="width:10%">NISN</th>
                <th>Name</th>
                <th style="width:20%">Gender</th>
                <th style="width:10%" class="text-center">Poin</th>
                <th style="width:10%" class="text-center">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-modal size="modal-lg">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <h5 class="alert-heading fw-bold"><i class="bx bxs-info-circle"></i> Perhatian!</h5>
                <hr class="m-2">
                <li>Inputan yang bertanda (<span class="text-danger"><b>*</b></span>) wajib diisi.</li>
                <li>Inputan yang bertanda <small class="text-muted">(opsional)</small> tidak wajib diisi.</li>
                <li>Foto yang diupload maksimal 5MB.</li>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <x-dropdownModal name="rombel_id" label="Rombel" opsi="true">
                @foreach ($rombel as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </x-dropdownModal>
            <x-dropdownModal name="siswa_id" label="Siswa" opsi="true"></x-dropdownModal>
            <x-dropdownModal name="pelanggaran_id" label="Pelanggaran" opsi="true">
                @foreach ($pelanggaran as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </x-dropdownModal>
            <div class="mb-3">
                <img class="d-block rounded" id="preview" alt="Image" width="120">
            </div>
            <x-input type="file" name="foto" label="Foto" value="" opsi="false"></x-input>
            <x-textarea name="keterangan" label="Catatan Keterangan" opsi="false"></x-textarea>
        </x-modal>
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
                        data: "rombel",
                        name: "rombel",
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
                var createHeading = "Isi Pelanggaran";
                createModel(createHeading)

                // Save
                saveImage("{{ route('point-pelanggaran-siswa.store') }}", myTable);

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
                            url: "{{ route('get-siswa.rombel', '') }}/" + rombel_id,
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

                // Preview Image
                $("#foto").change(function() {
                    var input = this;
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("#preview").attr("src", e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                });
            });
        </script>
    @endsection
