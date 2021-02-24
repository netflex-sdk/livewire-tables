<?php

namespace Netflex\Livewire\Tables\Traits;

/**
 * Trait Sorting.
 */
trait Sorting
{
    /**
     * Sorting.
     */

    /**
     * The initial field to be sorting by.
     *
     * @var string
     */
    public $sortField = 'id';

    /**
     * The initial direction to sort.
     *
     * @var bool
     */
    public $sortDirection = 'asc';

    /**
     * The default sort icon.
     *
     * @var string
     */
    public $sortDefaultIcon = '<i class="text-muted fas fa-sort"></i>';

    /**
     * The sort icon when currently sorting ascending.
     *
     * @var string
     */
    public $ascSortIcon = '<i class="fas fa-sort-up"></i>';

    /**
     * The sort icon when currently sorting descending.
     *
     * @var string
     */
    public $descSortIcon = '<i class="fas fa-sort-down"></i>';

    /**
     * @param $attribute
     */
    public function sort($attribute): void
    {
        if ($this->sortField !== $attribute) {
            $this->sortDirection = 'asc';
        } else {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }

        $this->sortField = $attribute;
    }

    /**
     * @return string
     */
    protected function getSortField(): string
    {
        return $this->sortField;
    }
}
