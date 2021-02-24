@if ($paginationEnabled || $searchEnabled)
    <div class="mb-4 d-flex w-100 justify-content-between">
        @if ($paginationEnabled && count($perPageOptions))
            <div class="d-none d-sm-flex flex-row align-items-center">
                <label for="perPage" class="me-2">
                    @lang('netflex-livewire-tables::strings.per_page')
                </label>
                <select wire:model="perPage" id="perPage" class="form-select w-auto" aria-label="{{ __('netflex-livewire-tables::strings.per_page') }}">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}">
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="d-flex flex-row flex-grow-1 ms-2">
            @if ($searchEnabled)
                @if ($clearSearchButton)
                    <div class="input-group">
                @endif

                <input
                    @if (is_numeric($searchDebounce) && $searchUpdateMethod === 'debounce') wire:model.debounce.{{ $searchDebounce }}ms="search" @endif
                    @if ($searchUpdateMethod === 'lazy') wire:model.lazy="search" @endif
                    @if ($disableSearchOnLoading) wire:loading.attr="disabled" @endif
                    class="form-control"
                    type="text"
                    autofocus
                    placeholder="{{ __('netflex-livewire-tables::strings.search') }}"
                />

                @if($clearSearchButton)
                        <button class="btn btn-secondary" type="button" wire:click="clearSearch">
                            @lang('laravel-livewire-tables::strings.clear')
                        </button>
                    </div>
                @endif
            @endif

            @include('netflex-livewire-tables::includes.export')
        </div>

    </div>
@endif
