<div
    class="{{ $this->getOption('container') ? 'container-fluid' : '' }}"
    @if (is_numeric($refresh)) wire:poll.{{ $refresh }}.ms @elseif(is_string($refresh)) wire:poll="{{ $refresh }}" @endif
>
    @include('netflex-livewire-tables::includes.offline')
    @include('netflex-livewire-tables::includes.options')

    @if($paginationLocation === 'header' || $paginationLocation === 'both')
        @include('netflex-livewire-tables::includes.pagination', ['label' => false])
    @endif

    @if ($this->getOption('responsive'))
        <div class="table-responsive">
    @endif
        @if($loadingIndicator)
        <div class="position-relative">
        @endif
            <table class="{{ $this->getOption('classes.table') }}">
                @include('netflex-livewire-tables::includes.thead')

                @include('netflex-livewire-tables::includes.loading')
                    @if($models->isEmpty())
                        @include('netflex-livewire-tables::includes.empty')
                    @else
                        @include('netflex-livewire-tables::includes.data')
                    @endif

                </tbody>

                @include('netflex-livewire-tables::includes.tfoot')
            </table>
            @include('netflex-livewire-tables::includes.loading-indicator')
        @if($loadingIndicator)
        </div>
        @endif

        @include('netflex-livewire-tables::includes.error')

    @if ($this->getOption('responsive'))
        </div>
    @endif

    @if($paginationLocation === 'footer' || $paginationLocation === 'both')
        @include('netflex-livewire-tables::includes.pagination')
    @endif
</div>
