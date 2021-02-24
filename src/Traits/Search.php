<?php

namespace Netflex\Livewire\Tables\Traits;

/**
 * Trait Search.
 */
trait Search
{
    /**
     * The initial search string.
     *
     * @var string
     */
    public $search = '';

    /**
     * Method to search by: debounce or lazy.
     * @var string
     */
    public $searchUpdateMethod = 'lazy';

    /**
     * Whether or not searching is enabled.
     *
     * @var bool
     */
    public $searchEnabled = true;

    /**
     * false = disabled
     * int = Amount of time in ms to wait to send the search query and refresh the table.
     *
     * @var int
     */
    public $searchDebounce = 500;

    /**
     * A button to clear the search box.
     *
     * @var bool
     */
    public $clearSearchButton = false;

    /**
     * Resets the search string.
     */
    public function clearSearch(): void
    {
        $this->search = '';
    }
}
