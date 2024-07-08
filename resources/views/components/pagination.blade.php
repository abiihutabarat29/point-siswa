<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            {{-- Tombol "previous" --}}
            @if ($data->onFirstPage())
                <li class="page-item disabled first">
                    <span class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></span>
                </li>
                <li class="page-item disabled prev">
                    <span class="page-link"><i class="tf-icon bx bx-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item first">
                    <a class="page-link" href="{{ $data->previousPageUrl() }}"><i
                            class="tf-icon bx bx-chevrons-left"></i></a>
                </li>
                <li class="page-item prev">
                    <a class="page-link" href="{{ $data->previousPageUrl() }}"><i
                            class="tf-icon bx bx-chevron-left"></i></a>
                </li>
            @endif

            {{-- Loop untuk menampilkan nomor halaman --}}
            @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $data->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Tombol "next" --}}
            @if ($data->hasMorePages())
                <li class="page-item next">
                    <a class="page-link" href="{{ $data->nextPageUrl() }}"><i
                            class="tf-icon bx bx-chevron-right"></i></a>
                </li>
                <li class="page-item last">
                    <a class="page-link" href="{{ $data->url($data->lastPage()) }}"><i
                            class="tf-icon bx bx-chevrons-right"></i></a>
                </li>
            @else
                <li class="page-item disabled next">
                    <span class="page-link"><i class="tf-icon bx bx-chevron-right"></i></span>
                </li>
                <li class="page-item disabled last">
                    <span class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
</div>
