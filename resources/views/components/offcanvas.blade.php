<div class="offcanvas offcanvas-end" tabindex="-1" id="ajaxModel" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="modelHeading"></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="ajaxModel" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-0">
        <form id="ajaxForm" name="ajaxForm" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="hidden_id" id="hidden_id">
            {{ $slot }}

            <div id="info-error" class="alert alert-warning text-danger" role="alert" style="display: none;">
            </div>
            <div class="d-flex justify-content-center align-items-center form-group mt-4">
                <button type="submit" class="btn btn-primary m-t-15 waves-effect" id="saveBtn" value="create">Simpan
                </button>
            </div>
        </form>
    </div>
</div>
