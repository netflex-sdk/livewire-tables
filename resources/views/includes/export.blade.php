@if (count($exports))
    <div class="d-none d-sm-block ms-2 dropdown table-export">
        <button class="dropdown-toggle {{ $this->getOption('classes.buttons.export') }}" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @lang('netflex-livewire-tables::strings.export', [])
        </button>

        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @foreach($exports as $export)
                @if(array_key_exists($export, $exportFormats))
                    <li>
                        <a class="dropdown-item" href="#" wire:click.prevent="export('{{ $export }}')">
                            @lang('netflex-livewire-tables::formats.' . $export)
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endif
