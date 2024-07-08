    @extends('layouts.app')
    @section('content')
        <x-card>
            <small class="text-light fw-medium">Pilih Kelas/Rombel</small>
            <div class="demo-inline-spacing ">
                @foreach ($rombel as $rom)
                    @if ($rom->id == $id)
                        <a href="{{ route('jadwal.rombel', Crypt::encrypt($rom->id)) }}" class="btn btn-sm btn-primary">
                            {{ $rom->name }} ({{ $rom->jurusan->name }})
                        </a>
                    @else
                        <a href="{{ route('jadwal.rombel', Crypt::encrypt($rom->id)) }}"
                            class="btn btn-sm btn-outline-primary">
                            {{ $rom->name }}
                        </a>
                    @endif
                @endforeach
            </div>
            <hr class="hr">
            <div class="card bg-transparent border border-primary">
                <div class="card-body">
                    @if (isset($id))
                        <form action="{{ route('jadwal.rombel', Crypt::encrypt($id)) }}" method="POST"
                            class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="hidden_id"
                                value="{{ isset($jadwal) ? Crypt::encrypt($jadwal->id) : '' }}">
                            <div class="row g-3" id="setting">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Durasi Mapel</span>
                                        <select class="form-select @error('durasi') is-invalid @enderror" name="durasi">
                                            <option selected disabled>::Pilih Durasi Mapel::</option>
                                            <option value="20" @selected(isset($jadwal) && $jadwal->durasi == 20)>
                                                20 Menit</option>
                                            <option value="25" @selected(isset($jadwal) && $jadwal->durasi == 25)>
                                                25 Menit</option>
                                            <option value="30" @selected(isset($jadwal) && $jadwal->durasi == 30)>
                                                30 Menit</option>
                                            <option value="35" @selected(isset($jadwal) && $jadwal->durasi == 35)>
                                                35 Menit</option>
                                            <option value="40" @selected(isset($jadwal) && $jadwal->durasi == 40)>
                                                40 Menit</option>
                                            <option value="45" @selected(isset($jadwal) && $jadwal->durasi == 45)>
                                                45 Menit</option>
                                            <option value="50" @selected(isset($jadwal) && $jadwal->durasi == 50)>
                                                50 Menit</option>
                                            <option value="55" @selected(isset($jadwal) && $jadwal->durasi == 55)>
                                                55 Menit</option>
                                            <option value="60" @selected(isset($jadwal) && $jadwal->durasi == 60)>
                                                60 Menit</option>
                                        </select>
                                        @error('durasi')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Jam Mulai</span>
                                        <select class="form-select @error('jam_mulai') is-invalid @enderror"
                                            name="jam_mulai">
                                            <option selected disabled>::Pilih Jam Mulai::</option>
                                            @for ($hour = 6; $hour <= 22; $hour++)
                                                @for ($minute = 0; $minute < 60; $minute += 15)
                                                    @php
                                                        $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
                                                        $formattedMinute = str_pad($minute, 2, '0', STR_PAD_LEFT);
                                                        $time = "$formattedHour:$formattedMinute";
                                                    @endphp
                                                    <option value="{{ $time }}" @selected(isset($jadwal) && $jadwal->jam_mulai == $time)>
                                                        {{ $time }}</option>
                                                @endfor
                                            @endfor
                                        </select>
                                        @error('jam_mulai')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Jlh. Mapel</span>
                                        <input type="text"
                                            class="form-control
                                        @error('jlh_mapel') is-invalid @enderror"
                                            name="jlh_mapel" id="jlhMapel" value="{{ $jadwal->jlh_mapel ?? '' }}">
                                        @error('jlh_mapel')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">Jlh. Istirahat
                                        </span>
                                        <select class="form-select @error('jlh_istirahat') is-invalid @enderror"
                                            name="jlh_istirahat" id="istirahat">
                                            <option selected disabled>::Pilih Durasi Istirahat::</option>
                                            <option value="1" @selected(isset($jadwal) && $jadwal->jlh_istirahat == 1)>
                                                1 Kali</option>
                                            <option value="2" @selected(isset($jadwal) && $jadwal->jlh_istirahat == 2)>
                                                2 Kali</option>
                                            <option value="3" @selected(isset($jadwal) && $jadwal->jlh_istirahat == 3)>
                                                3 Kali</option>
                                            <option value="4" @selected(isset($jadwal) && $jadwal->jlh_istirahat == 4)>
                                                4 Kali</option>
                                        </select>
                                        @error('jlh_istirahat')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="float-end mt-4">
                                <button type="submit" id="generate" class="btn rounded-pill btn-primary"><i
                                        class='bx bxs-analyse px-1'></i> Generate</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning text-center text-danger" role="alert">
                            Silahkan Pilih Kelas terlebih dahulu !!
                        </div>
                    @endif
                </div>
            </div>
            <hr class="hr">
            @if (isset($jadwal) && isset($jadwal_istirahat))
                <form id="ajaxForm" name="ajaxForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-center">
                                <tr>
                                    <th class="text-white">WAKTU</th>
                                    @foreach ($hari as $day)
                                        <th class="text-white">{{ $day->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $jadwal->jlh_mapel; $i++)
                                    @php
                                        $jam_ke = $i + 1;
                                        $istirahat = $jadwal_istirahat->where('jam_ke', $jam_ke)->first();
                                        $jam_mulai_iterasi = $i == 0 ? strtotime($jadwal->jam_mulai) : $jam_selesai;
                                        $durasi_per_kelas = $istirahat ? $istirahat->durasi : $jadwal->durasi;
                                        $jam_selesai = strtotime(
                                            '+' . $durasi_per_kelas . ' minutes',
                                            $jam_mulai_iterasi,
                                        );
                                    @endphp
                                    <tr data-jamke="{{ $jam_ke }}">
                                        <td class="{{ $istirahat ? 'bg-danger text-white' : 'bg-primary text-white' }} text-center"
                                            style="width:10%">
                                            {{ date('H:i', $jam_mulai_iterasi) }} - {{ date('H:i', $jam_selesai) }}
                                        </td>
                                        @foreach ($hari as $day)
                                            @php
                                                $bg_color = $istirahat ? 'bg-danger text-white' : '';
                                                $selectedMapelId =
                                                    $jadwal_mapels
                                                        ->where('hari_id', $day->id)
                                                        ->where('jam_ke', $jam_ke)
                                                        // ->first()->mapel_id ?? null;
                                                        ->first()->guru_mapel_id ?? null;
                                            @endphp
                                            <td class="text-center {{ $bg_color }}">
                                                @if (!$istirahat)
                                                    <select class="form-select form-select-sm mapel-select"
                                                        name="guru_mapel_id[]" data-idhari="{{ $day->id }}"
                                                        data-jamke="{{ $jam_ke }}"
                                                        data-jadwalid="{{ $jadwal->id }}">
                                                        <option selected disabled>::Pilih Mapel::</option>
                                                        @foreach ($mapel as $map)
                                                            <option value="{{ $map->id }}"
                                                                @selected($selectedMapelId == $map->id)>
                                                                {{ $map->mapel->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <div id="jam_istirahat" data-jam_istirahat="{{ $jam_ke }}">
                                                        Istirahat
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <div class="float-end mt-4">
                        <button id="saveBtn" class="btn rounded-pill btn-primary">
                            <i class='bx bx-save px-1'></i> Simpan
                        </button>
                    </div>
                </form>
            @else
                <div class="alert alert-warning text-center text-danger" role="alert">
                    Jadwal belum di GENERATE.
                </div>
            @endif
        </x-card>
    @endsection
    @section('script')
        <script text="javascript">
            $(document).ready(function() {
                @if ($message = Session::get('success'))
                    var message = "{{ $message }}"
                    alertToastr(message);
                @endif

                $("#generate").on("click", function() {
                    var selects = $("select[required]");
                    var inputs = $("input[required]");
                    var isValid = true;

                    selects.each(function() {
                        if ($(this).val() === null || $(this).val() === '') {
                            isValid = false;
                            $(this).addClass("is-invalid");
                            $(this).siblings('.invalid-feedback').text(
                                'Harap pilih opsi jam ke berapa istirahat.');
                        } else {
                            $(this).removeClass("is-invalid");
                            $(this).siblings('.invalid-feedback').text('');
                        }
                    });

                    inputs.each(function() {
                        if ($(this).val().trim() === '') {
                            isValid = false;
                            $(this).addClass("is-invalid");
                            $(this).siblings('.invalid-feedback').text('Harap isi durasi istirahat.');
                        } else {
                            $(this).removeClass("is-invalid");
                            $(this).siblings('.invalid-feedback').text('');
                        }
                    });

                    if (!isValid) {
                        return false;
                    }
                });

                $("#jlhMapel").on("input", function() {
                    let jlhMapel = parseInt($(this).val());
                    $("#jam").empty();
                    for (let i = 1; i <= jlhMapel; i++) {
                        $("#jam").append(new Option("Jam ke " + i, i));
                    }
                });

                var istirahat = {!! isset($jadwal) ? json_encode($jadwal) : '{}' !!};
                var jadwal_istirahat = istirahat && istirahat.jadwal_istirahat ? istirahat.jadwal_istirahat : null;

                if (jadwal_istirahat && jadwal_istirahat.length > 0) {
                    jadwal_istirahat.forEach(function(item, index) {
                        let jamLabel = jadwal_istirahat.length === 1 ? 'Jam Istirahat' :
                            `Jam Istirahat ${index + 1}`;
                        let durasiLabel = jadwal_istirahat.length === 1 ? 'Durasi Istirahat' :
                            `Durasi Istirahat ${index + 1}`;

                        let jamOptions = '';
                        for (let j = 1; j <= $("#jlhMapel").val(); j++) {
                            jamOptions +=
                                `<option value="${j}" ${item.jam_ke == j ? 'selected' : ''}>Jam ke ${j}</option>`;
                        }

                        $("#setting").append(
                            `<div class="col-md-4 to-remove">
                                <div class="input-group">
                                    <span class="input-group-text">${jamLabel}</span>
                                    <select class="form-select" name="jam_ke[]" id="jam${index + 1}" required>
                                        <option selected disabled>::Pilih ${jamLabel}::</option>
                                        ${jamOptions}
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4 to-remove">
                                <div class="input-group">
                                    <span class="input-group-text">${durasiLabel}</span>
                                    <input type="number" name="durasi_istirahat[]" class="form-control"
                                    value="${item.durasi}" required>
                                    <span class="input-group-text">Menit</span>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>`
                        );
                    });
                }

                $("#istirahat").on("change", function() {
                    let istirahat = parseInt($(this).val());
                    $('#setting .to-remove').remove();

                    for (let i = 1; i <= istirahat; i++) {
                        let jamLabel = istirahat === 1 ? 'Jam Istirahat' : `Jam Istirahat ${i}`;
                        let durasiLabel = istirahat === 1 ? 'Durasi Istirahat' : `Durasi Istirahat ${i}`;

                        let jamOptions = '';
                        for (let j = 1; j <= $("#jlhMapel").val(); j++) {
                            jamOptions += `<option value="${j}">Jam ke ${j}</option>`;
                        }

                        $("#setting").append(
                            `<div class="col-md-4 to-remove">
                            <div class="input-group">
                                <span class="input-group-text">${jamLabel}</span>
                                <select class="form-select" name="jam_ke[]" id="jam${i}" required>
                                    <option selected disabled>::Pilih ${jamLabel}::</option>
                                    ${jamOptions}
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4 to-remove">
                            <div class="input-group">
                                <span class="input-group-text">${durasiLabel}</span>
                                <input type="number" name="durasi_istirahat[]" class="form-control" required>
                                <span class="input-group-text">Menit</span>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>`
                        );
                    }
                });

                $('#saveBtn').on('click', function(e) {
                    e.preventDefault();
                    $(this).html(
                        "<span class='spinner-border spinner-border-sm'></span> menyimpan...</i></span>"
                    );

                    var dataToSend = [];

                    $('select[name="guru_mapel_id[]"]').each(function() {
                        var jamke = $(this).data('jamke');
                        var hariId = $(this).data('idhari')
                        var mapelId = $(this).val();

                        dataToSend.push({
                            jam_ke: jamke,
                            hari_id: hariId,
                            guru_mapel_id: mapelId
                        });
                    });

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('jadwal.mapel', Crypt::encrypt($id)) }}',
                        data: {
                            data: dataToSend,
                            _token: '{{ csrf_token() }}'
                        },
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
                                    $("#saveBtn").html("Simpan");
                                });
                            } else {
                                alertToastr(data.success);
                                $("#saveBtn").html("Simpan");
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
