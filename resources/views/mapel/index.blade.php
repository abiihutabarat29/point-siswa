    @extends('layouts.app')
    @section('content')
        <div class="row g-2">
            @include('mapel.kelompok')
            @include('mapel.subKelompok')
        </div>

        <x-card>
            <div class="dt-action-buttons">
                <div class="row g-2">
                    <x-importBtn></x-importBtn>
                    <x-createBtn></x-createBtn>
                </div>
            </div>
            <x-table>
                <th class="text-center" style="width:5%">#</th>
                <th class="text-center">Mata Pelajaran</th>
                <th class="text-center">Kode Mata Pelajaran</th>
                <th class="text-center">Kelompok</th>
                <th class="text-center">Status</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    @endsection
    @section('modal')
        <x-offcanvas>
            <x-inputPlaceholder type="text" name="name" label="Mata Pelajaran"
                placeholder="Bahasa Indonesia"></x-inputPlaceholder>
            <x-inputPlaceholder type="text" name="kode" label="Kode" placeholder="contoh : A1"></x-inputPlaceholder>
            <x-dropdown name="kelompok_id" label="Kelompok" value="">
                @foreach ($kelompok as $kel)
                    <option value="{{ $kel->id }}">{{ $kel->name }}</option>
                @endforeach
            </x-dropdown>
            <x-dropdown name="status" label="Status">
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </x-dropdown>
        </x-offcanvas>
        <x-delete></x-delete>
        @include('mapel.modalKelompok')
        @include('mapel.modalSubKelompok')
        <x-importModal file="{{ asset('data/format-mapel.csv') }}"></x-importModal>
    @endsection
    @section('script')
        <script text="javascript">
            $(document).ready(function() {
                const tableKel = $('#kelompok').DataTable({
                    serverSide: true,
                    displayLength: 10,
                    lengthMenu: [10, 25, 50, 75, 100],
                    processing: true,
                    ajax: "{{ url('mapel-kelompok') }}",
                    columns: [{
                            data: 'kategori',
                            name: 'kategori'
                        },
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false,
                        }
                    ]
                });

                const tableSub = $('.sub').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('mapel-subkelompok') }}",
                    columns: [{
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'kategori',
                            name: 'kategori'
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false,
                        }
                    ]
                });

                $("#create-kel").click(function() {
                    $("#saveKel").val("create-kel");
                    $("#HeadingKel").html("Tambah Kelompok");
                    $("#FormKel").trigger("reset");
                    $("#hidden_id").val("");
                    $("#modal-kel").modal("show");
                });

                $("#create-subkel").click(function() {
                    $("#saveSub").val("create-sub");
                    $("#HeadingSub").html("Tambah Sub Kelompok");
                    $("#FormSub").trigger("reset");
                    $("#hidden_id").val("");
                    $("#modal-sub").modal("show");
                });

                $("#saveKel").click(function(e) {
                    e.preventDefault();
                    $(this).html(
                        "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                    );

                    $.ajax({
                        data: $("#FormKel").serialize(),
                        url: "{{ route('mapelKelompok.store') }}",
                        type: "POST",
                        dataType: "json",
                        success: function(data) {
                            if (data.errors) {
                                $(".alert-danger").html("");
                                $.each(data.errors, function(key, value) {
                                    $(".alert-danger").show();
                                    $(".alert-danger").append(
                                        "<strong><li>" + value + "</li></strong>"
                                    );
                                    $(".alert-danger").fadeOut(5000);
                                    $("#saveKel").html("Simpan");
                                });
                            } else {
                                $("#modal-kel").modal("hide");
                                tableKel.draw();
                                alertToastr(data.success);
                                $("#saveKel").html("Simpan");
                            }
                        },
                    });
                });

                $("#saveSub").click(function(e) {
                    e.preventDefault();
                    $(this).html(
                        "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                    );

                    $.ajax({
                        data: $("#FormSub").serialize(),
                        url: "{{ route('mapelSubKelompok.store') }}",
                        type: "POST",
                        dataType: "json",
                        success: function(data) {
                            if (data.errors) {
                                $(".alert-danger").html("");
                                $.each(data.errors, function(key, value) {
                                    $(".alert-danger").show();
                                    $(".alert-danger").append(
                                        "<strong><li>" + value + "</li></strong>"
                                    );
                                    $(".alert-danger").fadeOut(5000);
                                    $("#saveSub").html("Simpan");
                                });
                            } else {
                                $("#modal-sub").modal("hide");
                                tableSub.draw();
                                alertToastr(data.success);
                                $("#saveSub").html("Simpan");
                            }
                        },
                    });
                });

                $("body").on("click", ".editKel", function() {
                    const editId = $(this).data("id");
                    $.get("mapel-kelompok" + "/" + editId + "/edit", function(data) {
                        $("#saveKel").val("editKel");
                        $("#hidden_idKel").val(data.id);
                        $("#HeadingKel").html("Edit Kelompok");
                        $("#modal-kel").modal("show");
                        $("#kodeKel").val(data.kode);
                        $("#nameKel").val(data.name);
                        $("#kategoriKel").val(data.kategori);
                    });
                });

                $("body").on("click", ".editSub", function() {
                    const editId = $(this).data("id");
                    $.get("mapel-kelompok" + "/" + editId + "/edit", function(data) {
                        $("#saveSub").val("editSub");
                        $("#hidden_idSub").val(data.id);
                        $("#HeadingSub").html("Edit Sub Kelompok");
                        $("#modal-sub").modal("show");
                        $("#kodeSub").val(data.kode);
                        $("#nameSub").val(data.name);
                        $("#parentSub").val(data.parent);
                    });
                });

                $("body").on("click", ".deleteKel", function() {
                    const deleteId = $(this).data("id");
                    $("#modelHeadingHps").html("Hapus");
                    $("#fitur").html("Kelompok");
                    $("#ajaxModelHps").modal("show");
                    $.get("mapel-kelompok" + "/" + deleteId + "/edit", function(data) {
                        $("#field").html(data.name);
                    });
                    $("#hapusBtn").click(function(e) {
                        e.preventDefault();
                        const csrfToken = $('meta[name="csrf-token"]').attr("content");

                        $(this).html(
                            "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menghapus...</i></span>"
                        );
                        $.ajax({
                            type: "DELETE",
                            url: "mapel-kelompok" + "/" + deleteId,
                            data: {
                                _token: csrfToken,
                            },
                            success: function(data) {
                                if (data.errors) {
                                    $(".alert-danger").html("");
                                    $.each(data.errors, function(key, value) {
                                        $(".alert-danger").show();
                                        $(".alert-danger").append(
                                            "<strong><li>" + value +
                                            "</li></strong>"
                                        );
                                        $(".alert-danger").fadeOut(5000);
                                        $("#hapusBtn").html(
                                            "<i class='fa fa-trash'></i>Hapus"
                                        );
                                    });
                                } else {
                                    tableKel.draw();
                                    tableSub.draw();
                                    alertToastr(data.success);
                                    $("#hapusBtn").html("<i class='fa fa-trash'></i>Hapus");
                                    $("#ajaxModelHps").modal("hide");
                                }
                            },
                        });
                    });
                });
            });

            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                var myTable = $('.datatable').DataTable({
                    processing: true,
                    displayLength: 20,
                    lengthMenu: [20, 30, 50, 75, 100],
                    serverSide: true,
                    scrollX: true,
                    ajax: {
                        url: '{{ route('mapel.index') }}',
                    },
                    columns: [{
                            "data": null,
                            "orderable": false,
                            "searchable": false,
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'kelompok',
                            name: 'kelompok'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: "action",
                            name: "action",
                            orderable: false,
                            searchable: false,
                        }
                    ],
                });

                // Create
                var createHeading = "Tambah {{ $menu }}";
                createModel(createHeading)

                // Edit
                var editUrl = "{{ route('mapel.index') }}";
                var editHeading = "Edit {{ $menu }}";
                var field = ['name', 'kode', 'kelompok_id', 'status'];
                editModel(editUrl, editHeading, field)

                // Save
                saveBtn("{{ route('mapel.store') }}", myTable);

                // Delete
                var fitur = "{{ $menu }}";
                var editUrl = "{{ route('mapel.index') }}";
                var deleteUrl = "{{ route('mapel.store') }}";
                Delete(fitur, editUrl, deleteUrl, myTable)

                var importHeading = "Import Mapel";
                importModel(importHeading)

                saveFile("{{ route('mapel.import') }}", myTable);
            });
        </script>
    @endsection
