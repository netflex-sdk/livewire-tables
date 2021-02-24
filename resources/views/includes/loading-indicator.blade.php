@if ($loadingIndicator && !$collapseDataOnLoading)
    <div wire:loading.class.remove="d-none" class="d-none position-absolute top-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0, 0, 0, 0.125)">
        <div class="text-center text-primary p-2" style="min-height: 3.5rem">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">
                    @lang('netflex-livewire-tables::strings.loading')
                </span>
            </div>
        </div>
    </div>
@endif