@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-utama rounded-lg shadow-md">
        
        {{-- Info Data --}}
        <div class="flex items-center gap-2 text-sm text-kedua">
            <i class="fa-solid fa-circle-info text-yellow-400"></i>
            <span>
                Menampilkan 
                <span class="font-bold text-yellow-400">{{ $paginator->firstItem() ?? 0 }}</span>
                sampai
                <span class="font-bold text-yellow-400">{{ $paginator->lastItem() ?? 0 }}</span>
                dari
                <span class="font-bold text-yellow-400">{{ $paginator->total() }}</span>
                data
            </span>
        </div>

        {{-- Pagination Controls --}}
        <div class="flex items-center gap-2">
            
            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-600 border border-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2 opacity-50">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-2 text-sm font-medium text-utama bg-kedua border border-kedua rounded-lg hover:bg-ketiga hover:border-ketiga transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif

            {{-- Page Numbers --}}
            <div class="flex items-center gap-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-3 py-2 text-sm font-medium text-kedua">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                {{-- Active Page --}}
                                <span class="px-4 py-2 text-sm font-bold text-utama bg-ketiga border-2 border-ketiga rounded-lg shadow-lg">
                                    {{ $page }}
                                </span>
                            @else
                                {{-- Other Pages --}}
                                <a href="{{ $url }}" 
                                   class="px-4 py-2 text-sm font-medium text-utama bg-kedua border border-kedua rounded-lg hover:bg-ketiga hover:border-ketiga transition-all duration-200 shadow-sm">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-2 text-sm font-medium text-utama bg-kedua border border-kedua rounded-lg hover:bg-ketiga hover:border-ketiga transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-600 border border-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2 opacity-50">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif

        </div>

        {{-- Mobile View - Simplified --}}
        <div class="sm:hidden w-full flex items-center justify-between">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-600 border border-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2 opacity-50">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-2 text-sm font-medium text-utama bg-kedua border border-kedua rounded-lg hover:bg-ketiga transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif

            <span class="px-4 py-2 text-sm font-bold text-utama bg-ketiga rounded-lg shadow-md">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-2 text-sm font-medium text-utama bg-kedua border border-kedua rounded-lg hover:bg-ketiga transition-all duration-200 flex items-center gap-2">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-600 border border-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2 opacity-50">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            @endif
        </div>

    </nav>
@endif

<style>
/* Smooth transitions untuk pagination - SCOPED */
nav[role="navigation"][aria-label*="Pagination"] .pagination-item {
    transition: all 0.2s ease-in-out;
}

nav[role="navigation"][aria-label*="Pagination"] .pagination-item:hover {
    transform: translateY(-1px);
}

/* Responsive adjustments - SCOPED untuk pagination saja */
@media (max-width: 640px) {
    nav[role="navigation"][aria-label*="Pagination"] > div:nth-child(2),
    nav[role="navigation"][aria-label*="Pagination"] > div:nth-child(3):not(.sm\:hidden) {
        display: none !important;
    }
    
    nav[role="navigation"][aria-label*="Pagination"] .sm\:hidden {
        display: flex !important;
    }
}

@media (min-width: 641px) {
    nav[role="navigation"][aria-label*="Pagination"] .sm\:hidden {
        display: none !important;
    }
}
</style>