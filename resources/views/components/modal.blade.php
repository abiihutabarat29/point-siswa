@props(['size'])
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog {{ $size }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelHeading"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ajaxForm" name="ajaxForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hidden_id" id="hidden_id">
                <hr class="m-0 mt-2">
                <div class="modal-body">
                    {{ $slot }}
                    <div id="info-error" class="alert alert-warning text-danger" role="alert" style="display: none;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="saveBtn" value="create" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
