<?php

namespace Netflex\Livewire\Tables\Traits;

use Netflex\Query\Builder;

/**
 * Trait Pagination.
 */
trait Pagination
{
    /**
     * Displays per page and pagination links.
     *
     * @var bool
     */
    public $paginationEnabled = true;

    /**
     * The options to limit the amount of results per page.
     *
     * @var array
     */
    public $perPageOptions = [10, 25, 50, 100, 250, 500];

    /**
     * Amount of items to show per page.
     *
     * @var int
     */
    public $perPage = 25;

    /**
     * Location of pagination, 'footer', 'header', or 'both'
     * @var string
     */
    public $paginationLocation = 'footer';

    /**
     * The initial page when pagination is enabled
     * @var int
     */
    public $page = 1;

    /**
     * https://laravel-livewire.com/docs/pagination
     * Resetting Pagination After Filtering Data.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * https://laravel-livewire.com/docs/pagination
     * Resetting Pagination After Changing the perPage.
     */
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }
}
