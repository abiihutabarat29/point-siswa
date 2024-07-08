    <div class="col mr-2">
        <x-card menu="{{ $menu }}">
            <div class="dt-action-buttons text-end pt-3 pt-md-0 mb-3">
                <div class="dt-buttons">
                    <x-createBtn></x-createBtn>
                </div>
            </div>
            <x-table>
                <th style="width:5%">#</th>
                <th>Tahun</th>
                <th>Status</th>
                <th class="text-center" style="width:5%">Action</th>
            </x-table>
        </x-card>
    </div>
