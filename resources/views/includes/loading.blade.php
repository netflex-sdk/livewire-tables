@if ($loadingIndicator)
    @if($collapseDataOnLoading)
    <tbody wire:loading.class.remove="d-none" class="d-none">
        <tr>
            <td colspan="{{ collect($columns)->count() }}">
                <div class="text-center text-primary p-2" style="min-height: 3.5rem">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">
                            @lang('netflex-livewire-tables::strings.loading')
                        </span>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
    @endif

    <tbody @if($collapseDataOnLoading) wire:loading.remove @endif>
@else
    <tbody>
@endif
