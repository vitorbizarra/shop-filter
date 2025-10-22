@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between mt-8">
            {{-- Mobile Pagination --}}
            <div class="flex justify-between flex-1 sm:hidden">
                @if ($paginator->onFirstPage())
                    <flux:button size="sm" disabled>
                        {!! __('pagination.previous') !!}
                    </flux:button>
                @else
                    <flux:button
                        size="sm"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled">
                        {!! __('pagination.previous') !!}
                    </flux:button>
                @endif

                @if ($paginator->hasMorePages())
                    <flux:button
                        size="sm"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled">
                        {!! __('pagination.next') !!}
                    </flux:button>
                @else
                    <flux:button size="sm" disabled>
                        {!! __('pagination.next') !!}
                    </flux:button>
                @endif
            </div>

            {{-- Desktop Pagination --}}
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <flux:text size="sm">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-semibold">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </flux:text>
                </div>

                <div class="flex items-center gap-1">
                    {{-- Previous Page Button --}}
                    @if ($paginator->onFirstPage())
                        <flux:button
                            size="sm"
                            icon="chevron-left"
                            square
                            disabled
                            aria-label="{{ __('pagination.previous') }}" />
                    @else
                        <flux:button
                            size="sm"
                            icon="chevron-left"
                            square
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            aria-label="{{ __('pagination.previous') }}" />
                    @endif

                    {{-- Pagination Numbers --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <flux:text size="sm" class="px-2">{{ $element }}</flux:text>
                        @endif

                        {{-- Array Of Page Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <flux:button
                                        size="sm"
                                        variant="primary"
                                        square
                                        aria-current="page"
                                        wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        {{ $page }}
                                    </flux:button>
                                @else
                                    <flux:button
                                        size="sm"
                                        square
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                        wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        {{ $page }}
                                    </flux:button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Button --}}
                    @if ($paginator->hasMorePages())
                        <flux:button
                            size="sm"
                            icon="chevron-right"
                            square
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            aria-label="{{ __('pagination.next') }}" />
                    @else
                        <flux:button
                            size="sm"
                            icon="chevron-right"
                            square
                            disabled
                            aria-label="{{ __('pagination.next') }}" />
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>
