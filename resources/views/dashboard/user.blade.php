    @extends('layouts.app')
    @section('content')
        <div class="col-lg-12 order-0">
            <div class="card">
                <div class="d-flex align-items-end">
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            @if (Auth::user()->guru->photo)
                                <div class="image-container">
                                    <img src="{{ url('storage/guru/' . Auth::user()->guru->photo) }}" alt="user-photo"
                                        class="rounded-circle shadow" height="120" width="120">
                                    <i class="bx bxs-badge-check icon-overlay icon-check"></i>
                                </div>
                            @else
                                <div class="image-container">
                                    <img src="{{ url('img/blank.jpg') }}" alt="user-photo" class="rounded-circle"
                                        height="120" width="120">
                                    <i class="bx bxs-x-circle icon-overlay icon-cross"></i>
                                </div>
                            @endif
                            <div class="button-wrapper">
                                <h5 class="card-title">
                                    @if (Auth::user()->guru)
                                        {{ Auth::user()->guru->nip }}
                                    @else
                                        "-"
                                    @endif
                                </h5>
                                <h5 class="card-title"> {{ Auth::user()->name }}
                                    <br>
                                    <span class="badge bg-label-success mt-2">
                                        @if (auth()->user()->role_id == 3)
                                            Guru
                                        @elseif (auth()->user()->role_id == 4)
                                            Bimbingan Konseling (BK)
                                        @elseif (auth()->user()->role_id == 5)
                                            Kepala Sekolah
                                        @endif
                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
