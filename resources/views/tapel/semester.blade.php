    <div class="col ml-2">
        <x-card>
            <div class="dt-action-buttons">
                <div class="row g-2">
                    <h5 class="col">Semester</h5>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semester as $sem)
                                <tr>
                                    <td>{{ $sem->name }}</td>
                                    <td class="text-center">
                                        @if ($sem->status == 1)
                                            <div class="col mb-0">
                                                <a href="{{ route('tapel.statussem', $sem->id) }}" class="text-success">
                                                    <i class="bx bx-check-double"></i> AKTIF
                                                </a>
                                            </div>
                                        @else
                                            <div class="col mb-0">
                                                <a href="{{ route('tapel.statussem', $sem->id) }}" type="button"
                                                    class="btn btn-sm btn-primary">
                                                    <span class="me-1"></span> AKTIFKAN
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>
        <x-card>
            <form action="{{ route('tapel.he') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" class="form-control" name="jumlah" value="{{ $hariEfektif->jumlah ?? 0 }}">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </x-card>
    </div>
