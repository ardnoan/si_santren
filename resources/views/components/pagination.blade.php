{{-- resources/views/components/pagination.blade.php --}}
@if ($paginator->hasPages())
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
    <!-- Info Text -->
    <div>
        <small class="text-muted">
            Menampilkan 
            <strong>{{ $paginator->firstItem() ?? 0 }}</strong> - 
            <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
            dari 
            <strong>{{ $paginator->total() }}</strong> 
            data
        </small>
    </div>

    <!-- Pagination Links -->
    <nav>
        <ul class="pagination pagination-sm mb-0">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">
                    <i class="bi bi-chevron-left"></i>
                </span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
            @endif

            {{-- Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link fw-bold">{{ $page }}</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </li>
            @endif
        </ul>
    </nav>
</div>
@endif