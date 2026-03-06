@if ($paginator->hasPages())
    <style>
        .pg-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 24px;
            flex-wrap: wrap;
            font-family: 'Inter', sans-serif;
        }

        .pg-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 600;
            color: #374151;
            text-decoration: none;
            transition: all .2s ease;
            cursor: pointer;
        }

        .pg-item:hover:not(.disabled):not(.active) {
            background: #F9FAFB;
            border-color: #D1D5DB;
            color: #111827;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .02);
        }

        .pg-item.active {
            background: #111827;
            color: #fff;
            border-color: #111827;
            box-shadow: 0 4px 10px rgba(17, 24, 39, .15);
            cursor: default;
        }

        .pg-item.disabled {
            background: #F9FAFB;
            color: #9CA3AF;
            border-color: #F3F4F6;
            cursor: not-allowed;
        }

        .pg-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            color: #6B7280;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .pg-icon {
            width: 16px;
            height: 16px;
        }

        @media (max-width: 640px) {
            .pg-item.mobile-hide {
                display: none;
            }
        }
    </style>

    <div class="pg-container">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pg-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <svg class="pg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pg-item" rel="prev" aria-label="@lang('pagination.previous')">
                <svg class="pg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pg-dots" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg-item active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pg-item mobile-hide">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pg-item" rel="next" aria-label="@lang('pagination.next')">
                <svg class="pg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span class="pg-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <svg class="pg-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </div>
@endif