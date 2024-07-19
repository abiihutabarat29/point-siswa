    @extends('layouts.app')
    @section('content')
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                <div class="d-flex align-items-end row">
                                    <div class="card-title">
                                        <h5 class="mb-2">Kelas/Rombel</h5>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <h4 class="mb-0">{{ $rombel }}</h4>
                                    </div>
                                </div>
                                <img src="assets/img/kelas.png" height="100" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                <div class="d-flex align-items-end row">
                                    <div class="card-title">
                                        <h5 class="mb-2">Guru</h5>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <h4 class="mb-0">{{ $guru }}</h4>
                                    </div>
                                </div>
                                <img src="assets/img/guru.png" height="100" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                <div class="d-flex align-items-end row">
                                    <div class="card-title">
                                        <h5 class="mb-2">Siswa</h5>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <h4 class="mb-0">{{ $siswa }}</h4>
                                    </div>
                                </div>
                                <img src="assets/img/siswa.png" height="100" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                <div class="d-flex align-items-end row">
                                    <div class="card-title">
                                        <h5 class="mb-2">User</h5>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <h4 class="mb-0">{{ $user }}</h4>
                                    </div>
                                </div>
                                <img src="assets/img/users.png" height="100" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 order-0">
            <div class="card">
                <div class="d-flex align-items-end">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang {{ Auth::user()->name }}</h5>
                            <p>
                                Silahkan lakukan penginputan Master Data!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
