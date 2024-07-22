@extends('layouts.app')
@section('content')
    <form action="{{ route('rombel.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        <div class="dt-action-buttons text-end pt-3 pt-md-0 mb-3">
            <div class="dt-buttons">
                <a href="{{ route('rombel.index') }}" type="button" class="btn btn-secondary me-1">Kembali
                </a>
                <button type="submit" class="btn btn-primary mb-3 float-end">
                    Simpan
                </button>
            </div>
        </div>
        <x-card>
            <div class="row g-2">
                <div class="col mb-0">
                    <input type="hidden" name="hidden_id" value="{{ $data->id }}" opsi="true">
                    <x-input type="text" name="name" label="Nama Kelas / Rombel" value="{{ $data->name }}"
                        opsi="true"></x-input>
                    <x-dropdown name="jurusan_id" label="Jurusan" opsi="true">
                        @foreach ($jurusan as $jur)
                            <option value="{{ $jur->id }}" @selected($data->jurusan_id == $jur->id)>{{ $jur->name }}
                            </option>
                        @endforeach
                    </x-dropdown>
                </div>
                <div class="col mb-0">
                    <x-dropdown name="kelas_id" label="Kelas" opsi="true">
                        @foreach ($kelas as $kel)
                            <option value="{{ $kel->id }}" @selected($data->kelas_id == $kel->id)>{{ $kel->name }}
                            </option>
                        @endforeach
                    </x-dropdown>
                </div>
            </div>
        </x-card>

        <div class="row g-2">
            <div class="col-md-8 mb-0">
                <x-card>
                    <div class="row g-0">
                        <div class="col-6">
                            <label for="semua" class="form-label">Semua Siswa</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Search</span>
                                <input type="text" class="form-control" placeholder="Cari Siswa" id="search-list"
                                    minlength="1" maxlength="24">
                            </div>
                            <select class="form-select ms-list" multiple id="the-list">
                                @foreach ($siswa as $sis)
                                    <option value="{{ $sis->id }}">{{ $sis->name }} ({{ $sis->nisn }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-1">
                            <div class="text-center">
                                <i class='bx bx-transfer'></i>
                            </div>
                        </div>
                        <div class="col-5">
                            <label for="jumlah" class="form-label">Jumlah Siswa : <span id="jlh">0</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Search</span>
                                <input type="text" class="form-control" placeholder="Cari Siswa" id="search-built-list"
                                    minlength="1" maxlength="24">
                            </div>
                            <select class="form-select ms-list @error('siswa_rombel[]') is-invalid @enderror"
                                name="siswa_rombel[]" multiple id="built-list">
                            </select>
                            @error('siswa_rombel[]')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </x-card>
            </div>
            <div class="col-md-4 mb-0">
                <x-card>
                    <x-dropdown name="siswa_id" label="Ketua Kelas" opsi="true"></x-dropdown>
                </x-card>
            </div>
        </div>
        <input type="hidden" name="selected_siswa" id="selected-siswa">
    </form>
@endsection
@section('script')
    <script text="javascript">
        $(document).ready(function() {
            let the_list = [];
            let selectedItems = [];
            let selectedSiswa = [];

            // Event handler untuk mengklik opsi pada the-list
            $("#the-list").on("click", "option", function() {
                let selectedId = $(this).val();
                let selectedItem = $(this).text();
                if (the_list.indexOf(selectedItem) === -1) {
                    the_list.push(selectedItem);
                    $("#built-list").append(new Option(selectedItem, selectedId));
                    $(this).remove();
                    updateJumlahSiswa();
                    $("#selected-siswa").val(function(index, currentValue) {
                        if (currentValue === '') {
                            return selectedId;
                        } else {
                            return currentValue + ',' +
                                selectedId;
                        }
                    });
                }
            });

            // Event handler untuk mengklik opsi pada built-list
            $("#built-list").on("click", "option", function() {
                let selectedId = $(this).val();
                let selectedItem = $(this).text();
                let index = $(this).index();
                $("#the-list option").eq(index).before(new Option(selectedItem, selectedId));
                $(this).remove();
                the_list = the_list.filter((item) => item !== selectedItem);
                updateJumlahSiswa();
                $("#selected-siswa").val(function(index, currentValue) {
                    let ids = currentValue.split(',');
                    let filteredIds = ids.filter(id => id !== selectedId);
                    return filteredIds.join(',');
                });
            });

            // Event handler untuk input pada search-list
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

            // Event handler untuk input pada search-built-list
            $("#search-built-list").on("input", function() {
                let query = $(this).val().toLowerCase();
                $("#built-list option").each(function() {
                    let optionText = $(this).text().toLowerCase();
                    if (optionText.indexOf(query) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            function updateJumlahSiswa() {
                let jumlahSiswa = $("#built-list option").length;
                $("#jlh").text(jumlahSiswa);
                ketuaKelas();
            }

            function ketuaKelas() {
                $("#siswa_id").empty();
                $("#siswa_id").append('<option selected disabled>::Pilih Ketua Kelas::</option>');
                $("#built-list option").each(function() {
                    let value = $(this).val();
                    let text = $(this).text();
                    $("#siswa_id").append(new Option(text, value));
                });
            }

            ketuaKelas();

            var siswa_id = {!! json_encode($data->siswa_id ?? null) !!};

            $.get("{{ route('siswa.rombel', $data->id) }}", function(data) {
                data.forEach(function(item) {
                    var option = new Option(item.siswa.name, item.siswa_id);
                    if (siswa_id == item.siswa_id) {
                        option.selected = true;
                    }
                    $("#built-list").append(new Option(item.siswa.name, item.siswa_id));
                    $("#siswa_id").append(option);
                });
            });
        });
    </script>
@endsection
