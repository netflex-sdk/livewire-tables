<?php

namespace Netflex\Livewire\Tables;

use Netflex\Query\PaginatedResult;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{
    public static function fromPaginatedResult(PaginatedResult $result, $onEachSide = 0): Paginator
    {
        $paginator = new static($result->data, $result->total, $result->per_page, $result->current_page);
        $paginator->onEachSide($onEachSide);

        return $paginator;
    }
}
