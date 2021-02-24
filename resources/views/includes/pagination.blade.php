@if ($paginationEnabled)
    <div class="d-flex align-items-baseline justify-content-between flex-column flex-md-row">
        @if($label ?? true)
            <div class="text-muted mb-3 mb-md-0">
                @lang('netflex-livewire-tables::strings.results', [
                    'first' => $models->count() ? $models->firstItem() : 0,
                    'last' => $models->count() ? $models->lastItem() : 0,
                    'total' => $models->total()
                ])
            </div>
        @endif
        <div class="ms-0 ms-md-auto">
            {{ $models->links() }}
        </div>
    </div>
@endif
