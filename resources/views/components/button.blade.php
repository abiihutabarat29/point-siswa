    @props(['label', 'url', 'icon'])
    <div class="col dt-buttons">
        <a href="{{ $url }}" class="dt-button btn btn-sm rounded-pill btn-primary" tabindex="0"
            aria-controls="DataTables_Table_0" type="button">
            <span><i class="bx {{ $icon }} me-1"></i>
                <span class="d-none d-lg-inline-block">{{ $label }}</span>
            </span>
        </a>
    </div>
