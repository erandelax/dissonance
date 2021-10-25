@php
/** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator */
@endphp
<div class="btn-toolbar mt-10 p-5 bg-light-lm rounded" role="toolbar" aria-label="Pagination"> <!-- mt-10 = margin-top: 1rem (10px), p-5 = padding: 0.5rem (5px), bg-light-lm = background-color: var(--gray-color-light) only in light mode, bg-very-dark-dm = background-color: var(--dark-color-dark) only in dark mode, rounded = rounded corners -->
    @if(!empty($batchActions))
    <div class="dropdown">
        <button class="btn" data-toggle="dropdown" type="button" id="dropdown-toggle-btn-1" aria-haspopup="true" aria-expanded="false">
            Batch actions <i class="fa fa-angle-down ml-5" aria-hidden="true"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="table actions">
            @foreach ($batchActions as $batchAction)
                @if($batchAction->isModal())
                @push('modals'){!! $batchAction->getAction()->render() !!}@endpush
                <a class="btn btn-block {{$batchAction->getStyle()}}" type="button" href="#{{$batchAction->getAction()->getID()}}">{!! $batchAction->getTitle() !!}</a>
                @else
                <button class="btn btn-block {{$batchAction->getStyle()}}" type="submit" name="form-action" value="{{$batchAction->getAction()}}">{!! $batchAction->getTitle() !!}</button>
                @endif
            @endforeach
        </div>
    </div>
    @endif
    @if($paginator->hasPages())
    <div class="input-group ml-auto"> <!-- ml-auto = margin-left: auto -->
        <div class="btn-group" role="group" aria-label="Button group as pagination">
            {{-- Previous Page Link --}}
            @if($paginator->currentPage() > 5)
                <a class="btn btn-square" href="{{$paginator->url( $paginator->currentPage() - 5 )}}" rel="prev" aria-label="&lsaquo; Skip 5">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">&lsaquo; Skip 5 </span> <!-- sr-only = only for screen readers -->
                </a>
            @endif
            @if ($paginator->onFirstPage())
                <span class="btn btn-square disabled">
                <i class="fa fa-angle-left" aria-hidden="true" aria-disabled="true" aria-label="@lang('pagination.previous')"></i>
                <span class="sr-only">&lsaquo;</span> <!-- sr-only = only for screen readers -->
            </span>
            @else
                <a class="btn btn-square" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">&lsaquo;</span> <!-- sr-only = only for screen readers -->
                </a>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a class="btn btn-square active" href="{{ $url }}" aria-current="page">{{ $page }}</a>
                        @else
                            <a class="btn btn-square" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="btn btn-square" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">&rsaquo;</span> <!-- sr-only = only for screen readers -->
                </a>
            @else
                <span class="btn btn-square disabled" aria-label="@lang('pagination.next')">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
                <span class="sr-only">&rsaquo;</span> <!-- sr-only = only for screen readers -->
            </span>
            @endif
            @if($paginator->lastPage() >= $paginator->currentPage()+5)
                <a class="btn btn-square" href="{{ $paginator->url( $paginator->currentPage() + 5 ) }}" rel="prev" aria-label="Skip 5  &rsaquo;">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">Skip 5 &rsaquo;</span>
                </a>
            @endif
        </div>
    </div>
    <div class="input-group ml-auto d-flex flex-row-reverse"> <!-- ml-auto = margin-left: auto -->
        <input type="number" min="1" max="{{$paginator->lastPage()}}" class="form-control" placeholder="Page" value="{{$paginator->currentPage()}}" style="max-width: 5rem">
    </div>
    @endif
</div>
