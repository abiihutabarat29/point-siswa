    @extends('layouts.app')
    @section('content')
        <div class="dt-action-buttons text-end pt-3 pt-md-0 mb-3">
            <div class="dt-buttons">
                <x-importBtn></x-importBtn>
                <x-createBtn></x-createBtn>
            </div>
        </div>
        <div class="row mb-5">
            @foreach ($guru as $gur)
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-3">
                                @if ($gur->photo == null)
                                    @if ($gur->gender == 'L')
                                        <img src="{{ url('assets/img/avatars/male.png') }}"
                                            class="img-fluid rounded mt-4 mx-3" alt="">
                                    @else
                                        <img src="{{ url('assets/img/avatars/female.png') }}"
                                            class="img-fluid rounded mt-4 mx-3" alt="">
                                    @endif
                                @else
                                    <img src="{{ url('storage/guru/' . $gur->photo) }}" class="img-fluid rounded mt-4 mx-3"
                                        alt="">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="px-4 mt-4">
                                    <div>{{ $gur->nip }}</div>
                                    <div class="fw-bold">{{ $gur->name }}</div>
                                    <div>
                                        <span class="badge bg-label-primary">
                                            @if ($gur->jabatan_guru)
                                                @if ($gur->jabatan_guru->where('tapel_id', $tapel->id)->where('semester_id', $semester->id)->count() > 0)
                                                    @if ($gur->jabatan_guru->jabatan_id == 4)
                                                        {{ $gur->jabatan_guru->jabatan->name }}
                                                        {{ $gur->jabatan_guru->rombel->name ?? '' }}
                                                    @else
                                                        {{ $gur->jabatan_guru->jabatan->name }}
                                                    @endif
                                                @else
                                                    Belum ada jabatan
                                                @endif
                                            @else
                                                Belum ada jabatan
                                            @endif
                                        </span>
                                    </div>
                                    <button type="button" class="btn btn-xs btn-outline-success mt-2">
                                        <i class='bx bx-check-double'></i>Aktif
                                    </button>
                                </div>
                            </div>
                            <div class="px-4 mt-2 mb-4">
                                @if ($gur->guru_mapel->isNotEmpty())
                                    @php
                                        $groupedGuruMapel = $gur->guru_mapel->groupBy(['mapel_id', 'rombel_id']);
                                        $no = 1;
                                    @endphp
                                    <p>Pengampu :</p>
                                    <div class="card-datatable">
                                        <table class="table table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width:5%">No.</th>
                                                    <th class="text-center">Mapel</th>
                                                    <th class="text-center">Kelas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($groupedGuruMapel as $key => $mapels)
                                                    @foreach ($mapels->first() as $map)
                                                        <tr>
                                                            <td class="text-center">{{ $no++ }}</td>
                                                            <td class="text-center">{{ $map->mapel->kode }}</td>
                                                            <td class="text-center">
                                                                @foreach ($mapels as $rombel)
                                                                    @foreach ($rombel as $rom)
                                                                        <span class="badge bg-primary m-1">
                                                                            <small>{{ $rom->rombel->name }}</small>
                                                                        </span>
                                                                    @endforeach
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <div class="demo-inline-spacing">
                                    <a href="{{ route('guru.show', Crypt::encrypt($gur->id)) }}"
                                        class="btn btn-xs btn-outline-primary">
                                        <span class="tf-icons bx bx-pencil me-1"></span>Profile
                                    </a>
                                    <a href="{{ route('guru.jabatan', Crypt::encrypt($gur->id)) }}" type="button"
                                        class="btn btn-xs btn-outline-primary">
                                        <span class="tf-icons bx bx-pencil me-1"></span>Jabatan
                                    </a>
                                    <button type="button" class="btn btn-xs btn-outline-danger delete float-end"
                                        data-bs-toggle="modal" data-bs-target="#ajaxModelHps"
                                        data-id="{{ $gur->id }}">
                                        <i class='bx bxs-trash-alt'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <x-pagination :data="$guru"></x-pagination>
        </div>
    @endsection
    @section('modal')
        <x-delete></x-delete>
        <x-modal size="modal-lg">
            <div class="row g-2">
                <div class="col mb-0">
                    <x-input type="text" name="nip" label="NIP" value="" opsi="true"></x-input>
                    <x-dropdown name="gender" label="Jenis Kelamin" opsi="true">
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </x-dropdown>
                    <x-input type="text" name="tlp" label="No. HP" value="" opsi="true"></x-input>
                    <x-image type="file" name="photo" label="Foto" value="" opsi="true"></x-image>
                </div>
                <div class="col mb-0">
                    <x-input type="text" name="name" label="Nama" value="" opsi="true"></x-input>
                    <x-input type="text" name="tmp_lahir" label="Tempat Lahir" value="" opsi="true"></x-input>
                    <x-input type="date" name="tgl_lahir" label="Tanggal Lahir" value="" opsi="true"></x-input>
                    <x-dropdown name="status" label="Status Guru" opsi="true">
                        <option value="PNS">PNS</option>
                        <option value="GTT">GTT</option>
                        <option value="Honor Sekolah">Honor Sekolah</option>
                    </x-dropdown>
                </div>
            </div>
        </x-modal>
        <x-importModal file="{{ asset('data/format-guru.csv') }}"></x-importModal>
    @endsection
    @section('script')
        <script text="javascript">
            @if ($message = Session::get('success'))
                var message = "{{ $message }}"
                alertToastr(message);
            @endif
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                // Create
                var createHeading = "Tambah {{ $menu }}";
                createModel(createHeading)

                $("#saveBtn").click(function(e) {
                    e.preventDefault();
                    $(this).html(
                        "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                    );
                    const form = $("#ajaxForm")[0];
                    const data = new FormData(form);
                    $.ajax({
                        data: data,
                        url: "{{ route('guru.store') }}",
                        type: "POST",
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            var errorAlert = $("#info-error");
                            errorAlert.html("");
                            if (data.errors) {
                                $.each(data.errors, function(key, value) {
                                    errorAlert.append(
                                        "<strong><li>" + value + "</li></strong>"
                                    );
                                });
                                errorAlert.show().fadeOut(5000);
                            } else {
                                errorAlert.hide();
                                $("#modal").modal("hide");
                                alertToastr(data.success);
                                location.reload();
                            }
                            $("#saveBtn").html("Simpan");
                        },
                    });
                });

                $("body").on("click", ".delete", function() {
                    const deleteId = $(this).data("id");
                    $("#modelHeadingHps").html("Hapus");
                    $("#fitur").html(fitur);
                    $("#ajaxModelHps").modal("show");
                    $.get("{{ route('guru.index') }}" + "/" + deleteId + "/edit", function(data) {
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
                            url: "{{ route('guru.store') }}" + "/" + deleteId,
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
                                    alertToastr(data.success);
                                    $("#hapusBtn").html("<i class='fa fa-trash'></i>Hapus");
                                    $("#ajaxModelHps").modal("hide");
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            },
                        });
                    });
                });

                var importHeading = "Imports Guru";
                importModel(importHeading)

                $("#saveFile").click(function(e) {
                    e.preventDefault();
                    $(this).html(
                        "<span class='spinner-border spinner-border-sm'></span><span class='visually-hidden'><i> menyimpan...</i></span>"
                    ).attr("disabled", "disabled");

                    var form = $("#FormImport")[0];
                    var data = new FormData(form);

                    $.ajax({
                        data: data,
                        url: "{{ route('guru.import') }}",
                        type: "POST",
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.errors) {
                                $("#info").html("");
                                $.each(data.errors, function(key, value) {
                                    $("#info").show();
                                    $("#info").append(
                                        "<strong><li>" + value + "</li></strong>"
                                    );
                                    $("#info").fadeOut(5000);
                                    $("#saveFile").html("Import").removeAttr("disabled");
                                });
                            } else {
                                alertToastr(data.success);
                                $("#saveFile").html("Import").removeAttr("disabled");
                                $("#modal-import").modal("hide");
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        },
                    });
                });
            });
        </script>
    @endsection
