    <div class="col">
        <x-card>
            <div class="dt-action-buttons">
                <div class="row g-2">
                    <h5 class="col">Kelompok Utama</h5>
                    <div class="col dt-buttons text-end">
                        <button class="dt-button btn btn-sm rounded-pill btn-primary" tabindex="0"
                            aria-controls="DataTables_Table_0" type="button" id="create-kel">
                            <span><i class="bx bx-plus me-1"></i>
                                <span class="d-none d-lg-inline-block">Tambah</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped" id="kelompok">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center" style="width:5%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </x-card>
    </div>
