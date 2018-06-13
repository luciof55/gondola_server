@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
        @else
			@if (isset($paginationFunction))
				<li class="page-item"><a class="page-link" onclick="{{$paginationFunction}}('{{ $paginator->currentPage() - 1 }}');" rel="prev">&lsaquo;</a></li>
			@else
				<li class="page-item"><a class="page-link" onclick="crudInstance.postFormPagination('{{ $paginator->currentPage() - 1 }}');" rel="prev">&lsaquo;</a></li>
			@endif
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
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
						@if (isset($paginationFunction))
							<li class="page-item"><a class="page-link" onclick="{{$paginationFunction}}('{{ $page }}');">{{ $page }}</a></li>
						@else
							<li class="page-item"><a class="page-link" onclick="crudInstance.postFormPagination('{{ $page }}');">{{ $page }}</a></li>
						@endif
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
			@if (isset($paginationFunction))
				<li class="page-item"><a class="page-link" onclick="{{$paginationFunction}}('{{ $paginator->currentPage() + 1 }}');" rel="next">&rsaquo;</a></li>
			@else
				<li class="page-item"><a class="page-link" onclick="crudInstance.postFormPagination('{{ $paginator->currentPage() + 1 }}');" rel="next">&rsaquo;</a></li>
			 @endif
        @else
            <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
        @endif
    </ul>
@endif
