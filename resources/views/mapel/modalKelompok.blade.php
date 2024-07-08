<div class="modal fade" id="modal-kel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="HeadingKel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="FormKel" name="FormKel" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="hidden_idKel" id="hidden_idKel">
                <div class="modal-body">
                    <x-inputPlaceholder type="text" name="kodeKel" label="Kode"
                        placeholder="contoh : A"></x-inputPlaceholder>
                    <x-inputPlaceholder type="text" name="nameKel" label="Nama"
                        placeholder="contoh : Kelompok A"></x-inputPlaceholder>
                    <x-dropdown name="kategoriKel" label="Kategori">
                        <option value="Wajib">Wajib</option>
                        <option value="Peminatan">Peminatan</option>
                        <option value="Mulok">Mulok</option>
                    </x-dropdown>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="saveKel" value="create-kel" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
