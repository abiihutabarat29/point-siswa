<div class="modal fade" id="modal-sub" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="HeadingSub"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormSub" name="FormSub" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hidden_idSub" id="hidden_idSub">
                <div class="modal-body">
                    <x-inputPlaceholder type="text" name="kodeSub" label="Kode"
                        placeholder="contoh : C1"></x-inputPlaceholder>
                    <x-inputPlaceholder type="text" name="nameSub" label="Nama"
                        placeholder="contoh : C1. Dasar Bidang Keahlian"></x-inputPlaceholder>
                    <x-dropdown name="parentSub" label="Kel. Utama">
                        @foreach ($kelompokSub as $kel)
                            <option value="{{ $kel->id }}">{{ $kel->name }}</option>
                        @endforeach
                    </x-dropdown>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="saveSub" value="create-sub" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
