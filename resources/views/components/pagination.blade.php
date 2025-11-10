{{-- resources/views/components/pagination.blade.php --}}
@if ($paginator->hasPages())
<div class="d-flex justify-content-between align-items-center mt-3">
  <div>
    <small class="text-muted">
      Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }}
      dari {{ $paginator->total() }} data
    </small>
  </div>
  <nav>
    <ul class="pagination pagination-sm mb-0">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
      <li class="page-item disabled">
        <span class="page-link">‹</span>
      </li>
      @else
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
      </li>
      @endif

      {{-- Pagination Elements --}}
      @foreach ($elements as $element)
      {{-- "Three Dots" Separator --}}
      @if (is_string($element))
      <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
      @endif

      {{-- Array Of Links --}}
      @if (is_array($element))
      @foreach ($element as $page => $url)
      @if ($page == $paginator->currentPage())
      <li class="page-item active">
        <span class="page-link">{{ $page }}</span>
      </li>
      @else
      <li class="page-item">
        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
      </li>
      @endif
      @endforeach
      @endif
      @endforeach

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
      </li>
      @else
      <li class="page-item disabled">
        <span class="page-link">›</span>
      </li>
      @endif
    </ul>
  </nav>
</div>
@endif