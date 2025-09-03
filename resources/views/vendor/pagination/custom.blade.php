
@if ($paginator->hasPages())
	<nav class="w-full">
		<ul class="pagination custom-pagination flex flex-wrap items-center justify-center gap-2 md:gap-3 my-4">
			{{-- Previous Page Link --}}
			@if ($paginator->onFirstPage())
				<li class="page-item disabled" aria-disabled="true">
					<span class="page-link px-4 py-2 border rounded-lg bg-white text-gray-400">&lt; Back</span>
				</li>
			@else
				<li class="page-item">
					<a class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt; Back</a>
				</li>
			@endif

			{{-- Pagination Elements --}}
			@php
				$total = $paginator->lastPage();
				$current = $paginator->currentPage();
				$max = 5;
				$half = floor($max / 2);
				$start = max(1, $current - $half);
				$end = min($total, $start + $max - 1);
				if ($end - $start < $max - 1) {
					$start = max(1, $end - $max + 1);
				}
			@endphp

			@if ($start > 1)
				<li class="page-item">
					<a class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" href="{{ $paginator->url(1) }}">1</a>
				</li>
				@if ($start > 2)
					<li class="page-item disabled"><span class="page-link bg-white border-0">...</span></li>
				@endif
			@endif

			@for ($i = $start; $i <= $end; $i++)
				@if ($i == $current)
					<li class="page-item active" aria-current="page">
						<span class="page-link px-4 py-2 border rounded-lg bg-blue-600 text-white font-bold shadow">{{ $i }}</span>
					</li>
				@else
					<li class="page-item">
						<a class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" href="{{ $paginator->url($i) }}">{{ $i }}</a>
					</li>
				@endif
			@endfor

			@if ($end < $total)
				@if ($end < $total - 1)
					<li class="page-item disabled"><span class="page-link bg-white border-0">...</span></li>
				@endif
				<li class="page-item">
					<a class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" href="{{ $paginator->url($total) }}">{{ $total }}</a>
				</li>
			@endif

			{{-- Next Page Link --}}
			@if ($paginator->hasMorePages())
				<li class="page-item">
					<a class="page-link px-4 py-2 border rounded-lg bg-white text-blue-600 hover:bg-blue-50" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &gt;</a>
				</li>
			@else
				<li class="page-item disabled" aria-disabled="true">
					<span class="page-link px-4 py-2 border rounded-lg bg-white text-gray-400">Next &gt;</span>
				</li>
			@endif
		</ul>
	</nav>
	<style>
		.custom-pagination .page-link {
			border: none;
			border-radius: 0.75rem;
			background: #fff;
			color: #222;
			font-weight: 500;
			min-width: 2.5rem;
			min-height: 2.5rem;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: none;
			transition: background 0.2s, color 0.2s;
		}
		.custom-pagination .page-item.active .page-link {
			background: #2563eb;
			color: #fff;
			font-weight: bold;
		}
		.custom-pagination .page-item.disabled .page-link {
			background: #f5f5f5;
			color: #bbb;
		}
		.custom-pagination .page-link:hover {
			background: #2563eb;
			color: #fff;
		}
	</style>
@endif
