    @extends('layouts.app')
    @section('content')
        <form action="{{ route('guru.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            <div class="row g-0">
                <div class="col-md-4 p-2">
                    <div>
                        <a href="{{ route('guru.index') }}" type="button" class="btn btn-secondary mb-3">
                            Kembali
                        </a>
                    </div>
                    <x-card>
                        <div class="user-avatar-section">
                            <div class=" d-flex align-items-center flex-column">
                                @if ($guru->photo == null)
                                    @if ($guru->gender == 'L')
                                        <div class="d-flex align-items-center">
                                            <img src="{{ url('assets/img/avatars/male.png') }}"
                                                class="img-fluid rounded my-4" width="130" alt="">
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <img src="{{ url('assets/img/avatars/female.png') }}"
                                                class="img-fluid rounded my-4" width="130" alt="">
                                        </div>
                                    @endif
                                @else
                                    <div class="d-flex align-items-center">
                                        <img src="{{ url('storage/guru/' . $guru->photo) }}" class="img-fluid rounded my-4"
                                            width="117" alt="">
                                    </div>
                                @endif
                                <div class="user-info text-center mb-3">
                                    <h4 class="mb-2">{{ $guru->name }}</h4>
                                    <span class="badge bg-label-secondary">
                                        @if ($guru->jabatan_guru)
                                            {{ $guru->jabatan_guru->jabatan->name }}
                                        @else
                                            Belum ada jabatan
                                        @endif
                                    </span>
                                </div>
                                <x-input type="file" name="photo" label="Ganti Foto" value=""
                                    opsi="false"></x-input>
                            </div>
                        </div>
                    </x-card>
                </div>
                <div class="col-md-8 p-2">
                    <input type="hidden" name="hidden_id" value="{{ $guru->id }}"></input>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="nav-align-top mb-4">
                        <div class="row g-2">
                            <div class="col">
                                <ul class="nav nav-pills mb-3" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-profile" aria-controls="navs-pills-profile"
                                            aria-selected="true">Home</button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-data" aria-controls="navs-pills-data"
                                            aria-selected="false">Profile
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary float-end">Simpan</button>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-pills-profile" role="tabpanel">
                                <x-inputHorizontal name="kode" value="{{ $guru->kode }}" label="Kode Guru"
                                    icon="bx-barcode"></x-inputHorizontal>
                                <x-inputHorizontal name="name" value="{{ $guru->name }}" label="Nama Lengkap"
                                    icon="bx-user"></x-inputHorizontal>
                                <x-inputHorizontal name="nip" value="{{ $guru->nip }}" label="NIP"
                                    icon="bx-id-card"></x-inputHorizontal>
                                <x-inputHorizontal name="tgl_lahir" value="{{ $guru->tgl_lahir }}" label="Tanggal Lahir"
                                    icon="bx-calendar"></x-inputHorizontal>
                                <x-dropdownHorizontal name="gender" label="Jenis Kelamin" icon="bx-male-female">
                                    <option value="L" @selected($guru->gender == 'L')>Laki-Laki</option>
                                    <option value="P" @selected($guru->gender == 'P')>Perempuan</option>
                                </x-dropdownHorizontal>
                                <x-dropdownHorizontal name="status" label="Status Guru" icon="bx-award">
                                    <option value="PNS" @selected($guru->status == 'PNS')>PNS</option>
                                    <option value="GTT" @selected($guru->status == 'GTT')>GTT</option>
                                    <option value="Honor Sekolah" @selected($guru->status == 'Honor Sekolah')>Honor Sekolah</option>
                                </x-dropdownHorizontal>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-data" role="tabpanel">
                                <x-inputHorizontal name="nik" value="{{ $guru->nik }}" label="NIK"
                                    icon="bx-id-card"></x-inputHorizontal>
                                <x-inputHorizontal name="tlp" value="{{ $guru->tlp }}" label="No. Handphone"
                                    icon="bx-phone"></x-inputHorizontal>
                                <x-inputHorizontal name="tmp_lahir" value="{{ $guru->tmp_lahir }}" label="Tempat Lahir"
                                    icon="bx-map"></x-inputHorizontal>
                                <x-inputHorizontal name="address" value="{{ $guru->address }}" label="Alamat"
                                    icon="bx-map"></x-inputHorizontal>
                                <x-dropdownHorizontal name="agama" value="{{ $guru->agama }}" label="Agama"
                                    icon="bx-user">
                                    <option value="1" @selected($guru->agama == 1)>Islam</option>
                                    <option value="2" @selected($guru->agama == 2)>Kristen</option>
                                    <option value="3" @selected($guru->agama == 3)>Katolik</option>
                                    <option value="4" @selected($guru->agama == 4)>Hindu</option>
                                    <option value="5" @selected($guru->agama == 5)>Buddha</option>
                                    <option value="6" @selected($guru->agama == 6)>Konghucu</option>
                                </x-dropdownHorizontal>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    @endsection
    @section('script')
        <script text="javascript">
            $(document).ready(function() {
                @if ($message = Session::get('success'))
                    var message = "{{ $message }}"
                    alertToastr(message);
                @endif
            });
        </script>
    @endsection
