<!-- Modal Tolak -->
<div class="modal fade" id="modal-tolak" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bxs-info-circle text-danger"></i> Verifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr>
            <form id="ajaxForm" name="ajaxForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <h6>Apakah anda yakin ingin menolak skor ini ? </h6>
                    {{ $slot }}
                    <div id="info-error" class="alert alert-info text-danger" role="alert" style="display: none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Kembali
                    </button>
                    <button type="button" class="btn btn-danger" id="tolakBtn">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
