@if ($tableHeaderEnabled)
    <thead class="{{ $this->getOption('classes.thead') }}">
        @include('netflex-livewire-tables::includes.columns')
    </thead>
@endif
