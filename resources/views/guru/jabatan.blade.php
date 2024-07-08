    @extends('layouts.app')
    @section('content')
        <form action="{{ route('guru.jabatan', $guru->id) }}" method="POST" class="form-horizontal"
            enctype="multipart/form-data">
            @csrf
            <div class="row g-0">
                <div class="col-md-6 p-2">
                    <a href="{{ route('guru.index') }}" type="button" class="btn btn-outline-danger">
                        <span class="tf-icons bx bx-reply-all"></span>Kembali
                    </a>
                </div>
                <div class="col-md-6 p-2">
                    <div class="col">
                        <button type="submit" class="btn rounded-pill btn-primary float-end">Simpan</button>
                    </div>
                </div>
                <div class="col-md-6 p-2">
                    <x-card>
                        <h6>Detail Guru</h6>
                        <div class="card-datatable table-responsive">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td style="width:30%">Nama</td>
                                            <td>{{ $guru->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIP</td>
                                            <td>{{ $guru->nip }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>
                                                @if ($guru->gender == 'L')
                                                    Laki-Laki
                                                @else
                                                    Perempuan
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>{{ $guru->status }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </x-card>
                    <x-card>
                        <div class="mb-3">
                            <label for="Mata Pelajaran" class="form-label">Mata Pelajaran</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Search</span>
                                <input type="text" class="form-control" placeholder="Cari Mapel" id="search-list"
                                    minlength="1" maxlength="24">
                            </div>
                            <select class="form-select ms-list" multiple id="the-list">
                                @foreach ($mapel as $map)
                                    <option value="{{ $map->id }}">{{ $map->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </x-card>
                </div>
                <div class="col-md-6 p-2">
                    <x-card>
                        <input type="hidden" name="guru_id" value="{{ $guru->id }}">
                        <x-dropdown name="jabatan_id" label="Jabatan">
                            @foreach ($jabatan as $jab)
                                <option value="{{ $jab->id }}"
                                    @if ($guru->jabatan_guru) @selected($guru->jabatan_guru->jabatan_id == $jab->id) @endif>
                                    {{ $jab->name }}
                                </option>
                            @endforeach
                        </x-dropdown>
                        <div class="form-group mb-2" style="display: none" id="rombel">
                            <label for="rombel_id" class="form-label">Kelas</label>
                            <select id="rombel_id" name="rombel_id"
                                class="form-select @error('rombel_id') is-invalid @enderror" required>
                                <option selected disabled>::Pilih Kelas::</option>
                                @foreach ($rombel as $rom)
                                    <option value="{{ $rom->id }}"
                                        @if ($guru->jabatan_guru) @selected($guru->jabatan_guru->rombel_id == $rom->id) @endif>
                                        {{ $rom->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rombel_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </x-card>
                    <x-card>
                        <div id="built-list"></div>
                    </x-card>
                </div>
            </div>
        </form>
    @endsection
    @section('script')
        <script text="javascript">
            $(function() {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $(document).ready(function() {
                    @if ($message = Session::get('success'))
                        var message = "{{ $message }}"
                        alertToastr(message);
                    @endif

                    var kelas = {!! json_encode($guru->jabatan_guru->jabatan_id ?? null) !!};

                    if (kelas === 4) {
                        $("#rombel").show();
                    }

                    $("#jabatan_id").change(function() {
                        let jabatan_id = $(this).val();
                        if (jabatan_id == 4) {
                            $("#rombel").show();
                        } else {
                            $("#rombel").hide();
                        }

                    });
                });

                let the_list = [];
                let selectedItems = [];

                $("#search-list").on("input", function() {
                    let query = $(this).val().toLowerCase();
                    $("#the-list option").each(function() {
                        let optionText = $(this).text().toLowerCase();
                        if (optionText.indexOf(query) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });


                function appendMapelToBuiltList(item, selectedId, checklist) {
                    var rombelHtml = '';
                    var rombel = {!! json_encode($rombel_mapel) !!};
                    rombel.forEach(function(rombelItem) {
                        var isChecked = checklist.includes(rombelItem.id);
                        rombelHtml +=
                            '<div class="col-md-3 form-check mt-1">' +
                            '<input class="form-check-input" type="checkbox" name="mapel_' +
                            selectedId + '[kelas_' + rombelItem.id + ']" value="' +
                            rombelItem.id + '" id="defaultCheck' +
                            rombelItem.id + '"' + (isChecked ? ' checked' : '') + '/>' +
                            '<label class="form-check-label" for="defaultCheck' +
                            rombelItem.id + '">' +
                            rombelItem.name +
                            '</label>' +
                            '</div>';
                    });

                    $("#built-list").append(
                        '<div class="form-group mb-2" id="list_mapel_' + selectedId +
                        '">' +
                        '<label for="" class="form-label">' + item +
                        '</label>' +
                        '<input type="hidden" name="mapel_' + selectedId + '" value="' +
                        selectedId + '"></input>' +
                        '<input type="hidden" name="rombel_ids_' + selectedId + '" value="' +
                        checklist.join(',') + '"></input>' +
                        '<div class="row">' +
                        rombelHtml +
                        '</div>' +
                        '<hr class="hr">' +
                        '</div>'
                    );
                }

                $("#the-list").on("click", "option", function() {
                    let selectedId = $(this).val();
                    let selectedItem = $(this).text();
                    let index = the_list.indexOf(selectedItem);

                    if ($(this).hasClass("selected")) {
                        $(this).removeClass("selected");
                    } else {
                        $(this).addClass("selected");
                    }

                    if ($("#list_mapel_" + selectedId).length === 0) {
                        if (the_list.indexOf(selectedItem) === -1) {
                            the_list.push(selectedItem);
                            $.get("{{ route('rombel.get') }}", function(data) {
                                data.forEach(function(item) {
                                    $("#mapel_" + selectedId).append(new Option(item.name, item
                                        .id));
                                });
                                appendMapelToBuiltList(selectedItem, selectedId, []);
                            });
                        }
                    } else {
                        $("#list_mapel_" + selectedId).remove();
                        the_list.splice(index, 1);
                    }
                });

                $.get("{{ route('guru.mapel', $guru->id) }}", function(data) {
                    Object.values(data).forEach(function(item) {
                        var rombelIds = Array.isArray(item.rombel_id) ? item.rombel_id : [item
                            .rombel_id
                        ];
                        appendMapelToBuiltList(item.mapel.name, item.mapel_id, rombelIds);

                        $("#the-list option").each(function() {
                            if ($(this).val() == item.mapel_id) {
                                $(this).addClass("selected");
                            }
                        });
                    });
                });

            });
        </script>
    @endsection
