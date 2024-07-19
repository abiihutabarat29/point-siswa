    @extends('layouts.app')
    @section('content')
        <div class="col-lg-12 order-0">
            <div class="card">
                <div class="d-flex align-items-end">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang {{ Auth::user()->name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
