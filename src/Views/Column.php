<?php

namespace Netflex\Livewire\Tables\Views;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

use Netflex\Livewire\Tables\Traits\CanBeHidden;

/**
 * Class Column.
 */
class Column
{
    use CanBeHidden;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var bool
     */
    protected $searchable = false;

    /**
     * @var bool
     */
    protected $raw = false;

    /**
     * @var bool
     */
    protected $includeInExport = true;

    /**
     * @var bool
     */
    protected $exportOnly = false;

    /**
     * @var
     */
    protected $formatCallback;

    /**
     * @var
     */
    protected $exportFormatCallback;

    /**
     * @var
     */
    protected $sortCallback;

    /**
     * @var null
     */
    protected $searchCallback;

    /**
     * @var array
     * @var array
     */
    protected $fields = [];

    /**
     * Column constructor.
     *
     * @param  string  $text
     * @param  string|null  $attribute
     */
    public function __construct(string $text, ?string $attribute)
    {
        $this->text = $text;
        $this->attribute = $attribute ?? Str::camel(Str::lower($text));
    }

    /**
     * @param  string  $text
     * @param  string|null  $attribute
     *
     * @return Column
     */
    public static function make(string $text, ?string $attribute = null): Column
    {
        return new static($text, $attribute);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute ?? Str::title($this->text);
    }

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return empty($this->fields) ? [$this->getAttribute()] : $this->fields;
    }

    /**
     * @return mixed
     */
    public function getSortCallback()
    {
        return $this->sortCallback;
    }

    /**
     * @return mixed
     */
    public function getSearchCallback()
    {
        return $this->searchCallback;
    }

    /**
     * @return bool
     */
    public function isFormatted(): bool
    {
        return is_callable($this->formatCallback);
    }

    /**
     * @return bool
     */
    public function hasExportFormat(): bool
    {
        return is_callable($this->exportFormatCallback);
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable === true;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable === true;
    }

    /**
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw === true;
    }

    /**
     * @param  callable  $callable
     *
     * @return $this
     */
    public function format(callable $callable): Column
    {
        $this->formatCallback = $callable;

        return $this;
    }

    /**
     * @param  callable  $callable
     *
     * @return $this
     */
    public function exportFormat(callable $callable): Column
    {
        $this->exportFormatCallback = $callable;

        return $this;
    }

    /**
     * @param string $field 
     *
     * @return Column 
     */
    public function field(string $field)
    {
        return $this->fields([$field]);
    }

    /**
     * @param string|string[] ...$fields
     *
     * @return Column
     */
    public function fields(...$fields): Column
    {
        $this->fields = Collection::make($this->fields)
            ->merge(Collection::make($fields)->flatten())
            ->unique()
            ->toArray();

        return $this;
    }

    /**
     * @param $model
     * @param $column
     *
     * @return mixed
     */
    public function formatted($model, $column)
    {
        return call_user_func_array($this->formatCallback, ['model' => $model, 'column' => $column]);
    }

    /**
     * @param $model
     * @param $column
     *
     * @return mixed
     */
    public function formattedForExport($model, $column)
    {
        return call_user_func_array($this->exportFormatCallback, ['model' => $model, 'column' => $column]);
    }

    /**
     * @param  callable|null  $callable
     *
     * @return $this
     */
    public function sortable(callable $callable = null): self
    {
        $this->sortCallback = $callable;
        $this->sortable = true;

        return $this;
    }

    /**
     * @param  callable|null  $callable
     *
     * @return $this
     */
    public function searchable(callable $callable = null): self
    {
        $this->searchCallback = $callable;
        $this->searchable = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function raw(): self
    {
        $this->raw = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function includedInExport(): bool
    {
        return $this->includeInExport === true;
    }

    /**
     * @return $this
     */
    public function exportOnly(): self
    {
        $this->hidden = true;
        $this->exportOnly = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExportOnly(): bool
    {
        return $this->exportOnly === true;
    }

    /**
     * @return $this
     */
    public function excludeFromExport(): self
    {
        $this->includeInExport = false;

        return $this;
    }
}
