@props(['file'])

<div class="modal fade" id="modal-import" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importHeading"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormImport" name="FormImport" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col">
                            <label for="file" class="mb-2">File Excel</label>
                            <div class="d-grid">
                                <a href="{{ $file }}" class="btn btn-primary">
                                    <span class="tf-icons bx bxs-download me-1"></span>Download Template
                                </a>
                            </div>
                        </div>
                        <div class="col">
                            <x-input type="file" name="file" label="Import File" value=""
                                opsi="true"></x-input>
                        </div>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="saveFile" value="create-import" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
