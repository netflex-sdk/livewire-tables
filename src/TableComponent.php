<?php

namespace Netflex\Livewire\Tables;

use Exception;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

use Livewire\Component;
use Livewire\WithPagination;

use Netflex\Query\Builder;
use Netflex\Livewire\Tables\Traits\Exports;
use Netflex\Livewire\Tables\Traits\Loading;
use Netflex\Livewire\Tables\Traits\Options;
use Netflex\Livewire\Tables\Traits\Pagination;
use Netflex\Livewire\Tables\Traits\Search;
use Netflex\Livewire\Tables\Traits\Sorting;
use Netflex\Livewire\Tables\Traits\Table;
use Netflex\Livewire\Tables\Views\Column;
use Netflex\Query\Exceptions\QueryException;

/**
 * Class TableComponent.
 */
abstract class TableComponent extends Component
{
    use Exports,
        Loading,
        Options,
        Pagination,
        Search,
        Sorting,
        Table,
        WithPagination;

    /**
     * The default pagination theme.
     *
     * @var string|null
     */
    public $paginationTheme;

    /**
     * Whether or not to refresh the table at a certain interval
     * false is off
     * If it's an integer it will be treated as milliseconds (2000 = refresh every 2 seconds)
     * If it's a string it will call that function every 5 seconds.
     *
     * @var bool|string
     */
    public $refresh = false;

    /**
     * Whether or not to display an offline message when there is no connection.
     *
     * @var bool
     */
    public $offlineIndicator = true;

    /**
     * Wheter or not to retrieve all fields from the model when performing the query
     *
     * @var bool
     */
    public $fetchAllFields = true;

    /**
     * Exposed query parameters
     * @var string[]
     */
    public $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'perPage' => ['except' => 25]
    ];

    /**
     * TableComponent constructor.
     *
     * @param  null  $id
     */
    public function __construct($id = null)
    {
        $this->paginationTheme = 'bootstrap';

        $this->setOptions($this->options);

        parent::__construct($id);
    }

    /**
     * @return \Netflex\Query\Builder
     */
    abstract public function query();

    /**
     * @return array
     */
    abstract public function columns(): array;

    /**
     * @return string
     */
    public function view()
    {
        return 'netflex-livewire-tables::table-component';
    }

    /**
     * Jump to next page
     *
     * @return void
     */
    public function next()
    {
        $this->page++;
    }

    /**
     * Jump to previous page
     *
     * @return void 
     */
    public function previous()
    {
        $this->page--;
    }

    /**
     * Jump to a specific page
     * @param int $page 
     * @return void 
     */
    public function jump($page)
    {
        $this->page = $page;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render(): View
    {
        $models = Collection::make();
        $error = null;

        try {
            $models = $this->paginationEnabled
                ? Paginator::fromPaginatedResult($this->models()->paginate($this->perPage, $this->page))
                : $this->models()->get();
        } catch (QueryException $e) {
            $solution = $e->getSolution();
            $error = new HtmlString('<strong>' . $solution->getSolutionTitle() . '</strong> ' . $solution->getSolutionDescription());
            $models = $this->paginationEnabled
                ? new Paginator([], 0, 0, 1)
                : Collection::make();
        } catch (Exception $e) {
            $error = new HtmlString('<strong>' . $e->getMessage());
            $models = $this->paginationEnabled
                ? new Paginator([], 0, 0, 1)
                : Collection::make();
        }

        return view($this->view(), [
            'columns' => $this->columns(),
            'models' => $models,
            'error' => $error
        ]);
    }

    public function setFields(Builder $builder)
    {
        if (!$this->fetchAllFields) {
            $builder->macro('getFields', function () {
                return $this->fields ?? [];
            });

            $builder->macro('setFields', function (array $fields) {
                $this->fields = $fields;
                return $this;
            });

            $fields = Collection::make($builder->getFields());

            foreach ($this->columns() as $column) {
                $fields = $fields->merge($column->getFields());
            }

            $fields = $fields->flatten()
                ->unique()
                ->toArray();

            return $builder->setFields($fields);
        }

        return $builder;
    }

    /**
     * @return Builder
     */
    public function models(): Builder
    {
        /** @var Builder */
        $builder = $this->setFields($this->query());

        $columns = Collection::make($this->columns());

        if ($this->searchEnabled && trim($this->search) !== '') {
            $searchableColumns = $columns->filter(function (Column $column) {
                return $column->isSearchable();
            })->values();

            if ($searchableColumns->count()) {
                $builder->where(function (Builder $builder) use ($searchableColumns) {
                    foreach ($searchableColumns as $index => $column) {
                        if (is_callable($column->getSearchCallback())) {
                            call_user_func_array(
                                [$builder, ($index ? 'orWhere' : 'where')],
                                [
                                    function (Builder $builder) use ($column) {
                                        return App::call($column->getSearchCallback(), ['builder' => $builder, 'term' => trim($this->search)]);
                                    }
                                ]
                            );
                        } else {
                            call_user_func_array(
                                [$builder, ($index ? 'orWhere' : 'where')],
                                [
                                    $column->getAttribute(),
                                    Builder::OP_LIKE,
                                    implode('', ['*', trim($this->search), '*'])
                                ]
                            );
                        }
                    }
                });
            }
        }

        foreach ($columns as $column) {
            if ($column->getAttribute() === $this->sortField && is_callable($column->getSortCallback())) {
                return App::call($column->getSortCallback(), ['builder' => $builder, 'direction' => $this->sortDirection]);
            }
        }

        return $builder->orderBy($this->getSortField(), $this->sortDirection);
    }
}
