<div class="modal fade" id="modal-scan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <i class="bx bx-qr"></i>
                &nbsp;<h6 class="modal-title" id="modalHeading"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- <div id="info-error" class="alert alert-dismissible fade show" role="alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div id="info-error" class="alert alert-warning text-danger" role="alert" style="display: none;">
                </div>
                <h6 id="result-qr"class="text-center text-success"></h6>
                <div id="reader" width="600px"></div>
                <div id="qr-message" class="mt-3 text-muted text-center"></div>
            </div>
        </div>
    </div>
</div>
