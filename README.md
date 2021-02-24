# Netflex Livewire Tables

[![Latest Version on Packagist](https://img.shields.io/packagist/v/netflex/livewire-tables.svg?style=flat-square)](https://packagist.org/packages/netflex/livewire-tables)
[![Latest release](https://img.shields.io/github/v/release/netflex/livewire-tables?sort=semver&style=flat-square)](https://github.com/netflex-sdk/structures/releases/latest)
[![Total Downloads](https://img.shields.io/packagist/dt/netflex/livewire-tables.svg?style=flat-square)](https://packagist.org/packages/netflex/livewire-tables)


A datatables Livewire component for the Netflex SDK.

This plugin assumes you already have [Laravel Livewire](https://laravel-livewire.com/) installed and configured in your project.

## Installation

You can install the package via composer:

``` bash
composer require netflex/livewire-tables
```

## Publishing Assets

Publishing assets are optional unless you want to customize this package.

``` bash
php artisan vendor:publish --provider="Netflex\Livewire\Tables\LivewireTablesServiceProvider" --tag=views

php artisan vendor:publish --provider="Netflex\Livewire\Tables\LivewireTablesServiceProvider" --tag=lang
```

## Usage

### Creating Tables

To create a table component you draw inspiration from the below stub:

```php
<?php

namespace App\Http\Livewire;

use App\Article;
use Netflex\Query\Builder;

use Netflex\Livewire\Tables\TableComponent;
use Netflex\Livewire\Tables\Traits\HtmlComponents;
use Netflex\Livewire\Tables\Views\Column;

class ArticleTable extends TableComponent
{
    use HtmlComponents;

    public function query() : Builder
    {
        return Article::query()->ignorePublishingStatus();
    }

    public function columns() : array
    {
        return [
            Column::make('ID')
                ->searchable()
                ->sortable(),
            Column::make('Image')
                ->format(function(Article $article) {
                    return $this->image($article->image, 'tableArticlePreset', $article->name, ['class' => 'img-fluid']);
                }),
            Column::make('Name')
                ->searchable()
                ->sortable(),
            Column::make('E-mail', 'email')
                ->searchable()
                ->sortable()
                ->format(function(Article $article) {
                    return $this->mailto($article->email, null, ['target' => '_blank']);
                }),
            Column::make('Actions')
                ->format(function(Article $article) {
                    return view('backend.auth.user.includes.actions', ['article' => $article]);
                })
                ->hideIf(!auth()->user()),
        ];
    }
}
```

Your component must implement two methods:

```php
/**
 * This defines the start of the query, usually Model::query() but can add additonal constraints to the query.
 */
public function query() : Builder;

/**
 * This defines the columns of the table, they don't necessarily have to map to fields in the model structure.
 */
public function columns() : array;
```

### Rendering the Table

Place the following where you want the table to appear.

Netflex SDK 3.x

`<livewire:article-table />`

### Defining Columns

You can define the columns of your table with the column class.

The following methods are available to chain to a column:

```php

/**
 * The first argument is the column header text
 * The attribute can be omitted if the text is equal to the lower case snake_cased version of the column
 * The attribute can also be used to reference a relationship (i.e. role.name)
 */
public function make($text, ?$attribute) : Column;

/**
 * Used to format the column data in different ways, see the HTML Components section.
 * You will be passed the current model and column (if you need it for some reason) which can be omitted as an argument if you don't need it.
 */
public function format(callable $callable = null) : self;

/**
 * This column is searchable, with no callback it will search the column by name or by the supplied relationship, using a callback overrides the default searching functionality.
 */
public function searchable(callable $callable = null) : self;

/**
 * This column is sortable, with no callback it will sort the column by name and sort order defined on the components $sortDirection variable
 */
public function sortable(callable $callable = null) : self;

/**
 * The columns output will be put through {!! !!} instead of {{ }}.
 */
public function raw() : self;

/**
 * Hide this column permanently
 */
public function hide() : self;

/**
 * Hide this column based on a condition. i.e.: user has or doesn't have a role or permission. Must return a boolean, not a closure.
 */
public function hideIf($condition) : self;

/**
 * This column is only included in exports and is not available to the UI
 */
public function exportOnly() : self;

/**
 * This column is excluded from the export but visible to the UI unless defined otherwise with hide() or hideIf()
 */
public function excludeFromExport() : self;

/**
 * If supplied, and the column is exportable, this will be the format when rendering the CSV/XLS/PDF instead of the format() function. You may have both, format() for the UI, and exportFormat() for the export only. If this method is not supplied, format() will be used and passed through strip_tags() to try to clean the output.
 */
public function exportFormat(callable $callable = null) : self;
```

## Properties

You can override any of these in your table component:

### Table

| Property | Default | Usage |
| -------- | ------- | ----- |
| $tableHeaderEnabled | true | Whether or not to display the table header |
| $tableFooterEnabled | false | Whether or not to display the table footer |

### Searching

| Property | Default | Usage |
| -------- | ------- | ----- |
| $searchEnabled | true | Whether or not searching is enabled |
| $searchUpdateMethod | lazy | debounce or lazy |
| $searchDebounce | 500 | Amount of time in ms to wait to send the search query and refresh the table |
| $disableSearchOnLoading | true | Whether or not to disable the search bar when it is searching/loading new data | 
| $search | *none* | The initial search string |
| $clearSearchButton | false | Adds a clear button to the search input |
| $clearSearchButtonClass | btn btn-outline-dark | The class applied to the clear button |

### Sorting

| Property | Default | Usage |
| -------- | ------- | ----- |
| $sortField | id | The initial field to be sorting by |
| $sortDirection | asc | The initial direction to sort |
| $sortDefaultIcon | `<i class="text-muted fas fa-sort"></i>` | The default sort icon |
| $ascSortIcon | `<i class="fas fa-sort-up"></i>` | The sort icon when currently sorting ascending |
| $descSortIcon | `<i class="fas fa-sort-down"></i>` | The sort icon when currently sorting descending |

### Pagination

| Property | Default | Usage |
| -------- | ------- | ----- |
| $paginationEnabled | true | Enables or disables pagination as a whole |
| $perPageOptions | [10, 25, 50, 100, 250, 500] | The options to limit the amount of results per page. Set to [] to disable. |
| $perPage | 25 | Amount of items to show per page |
| $paginationLocation | 'footer' | Determines location of pagination, can be 'header', 'footer', or 'both' |

### Loading

| Property | Default | Usage |
| -------- | ------- | ----- |
| $loadingIndicator | true | Whether or not to show a loading indicator when searching |
| $disableSearchOnLoading | true | Whether or not to disable the search bar when it is searching/loading new data |
| $collapseDataOnLoading | false | When the table is loading, hide all data but the loading row |

### Offline

| Property | Default | Usage |
| -------- | ------- | ----- |
| $offlineIndicator | true | Whether or not to display an offline message when there is no connection |

### Exports

| Property | Default | Usage |
| -------- | ------- | ----- |
| $exportFileName | data | The name of the downloaded file when exported |
| $exports | [] | The available options to export this table as (csv, xls, xlsx, pdf) |
| $exporter | `Netflex\Livewire\Tables\Exports\Exporter` | The exporter class to use when generating the output. Must implement the `Netflex\Livewire\Tables\Contracts\Exporter` contract |
| $exportFormats | [...] | Key value array `['format' => Format::class]`, can be modified to add support for new formats, default configuation contains formats for PDF, CSV, XLS, and XLSX |

### Other

| Property | Default | Usage |
| -------- | ------- | ----- |
| $refresh | false | Whether or not to refresh the table at a certain interval. false = off, int = ms, string = functionCall |

### Columns/Data

Use the following methods to alter the column/row metadata.

```php
public function setTableHeadClass($attribute): ?string
public function setTableHeadId($attribute): ?string
public function setTableHeadAttributes($attribute): array
public function setTableRowClass($article): ?string
public function setTableRowId($article): ?string
public function setTableRowAttributes($article): array
public function getTableRowUrl($article): ?string
public function setTableDataClass($attribute, $value): ?string
public function setTableDataId($attribute, $value): ?string
public function setTableDataAttributes($attribute, $value): array
```

### Pagination

Override these methods if you want to perform extra tasks when the search or per page attributes change.

```php
public function updatingSearch(): void
public function updatingPerPage(): void
```

### Search

Override this method if you want to perform extra steps when the search has been cleared.

```php
public function clearSearch(): void
```

### Sorting

Override this method if you want to change the default sorting behavior.

```php
public function sort($attribute): void
```

### HTML Components

This package includes some of the functionality from the laravelcollective/html package modified to fit the needs of this package.

To use these you must import the *Netflex\Livewire\Tables\Traits\HtmlComponents* trait.

You may return any of these functions from the format() method of a column:

```php
public function image($pathOrMediaUrlResolvable, $presetOrSize, $alt = null, $attributes = []): HtmlString
public function link($url, $title = null, $attributes = [], $secure = null, $escape = true): HtmlString
public function secureLink($url, $title = null, $attributes = [], $escape = true): HtmlString
public function linkAsset($url, $title = null, $attributes = [], $secure = null, $escape = true): HtmlString
public function linkSecureAsset($url, $title = null, $attributes = [], $escape = true): HtmlString
public function linkRoute($name, $title = null, $parameters = [], $attributes = [], $secure = null, $escape = true): HtmlString
public function linkAction($action, $title = null, $parameters = [], $attributes = [], $secure = null, $escape = true): HtmlString
public function mailto($email, $title = null, $attributes = [], $escape = true): HtmlString
public function email($email): string
public function html($html): HtmlString
```

### Exporting Data

The table component supports exporting to CSV, XLS, XLSX, and PDF.

In order to use this functionality you must install [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/en/latest/) 1.0 or newer.

### What exports your table supports

By default, exporting is off. You can add a list of available export types with the $exports class property.

`public $exports = ['csv', 'xls', 'xlsx', 'pdf'];`

### Defining the file name.

By default, the filename will be `data`.*type*. I.e. `data.pdf`, `data.csv`.

You can change the filename with the `$exportFileName` class property.

`public $exportFileName = 'users-table';` - *Obviously omit the file type*

### Deciding what columns to export

You have a couple option on exporting information. By default, if not defined at all, all columns will be exported.

If you have a column that you want visible to the UI, but not to the export, you can chain on `excludeFromExport()`

If you have a column that you want visible to the export, but not to the UI, you can chain on `exportOnly()`

### Formatting column data for export

By default, the export will attempt to render the information just as it is shown to the UI. For a normal column based attribute this is fine, but when exporting formatted columns that output a view or HTML, it will attempt to strip the HTML out.

Instead, you have available to you the `exportFormat()` method on your column, to define how you want this column to be formatted when outputted to the file.

So you can have a column that you want both available to the UI and the export, and format them differently based on where it is being outputted.

### Exporting example

```php
<?php

namespace App\Http\Livewire;

use App\Article;

use Netflex\Query\Builder;
use Netflex\Livewire\Tables\TableComponent;
use Netflex\Livewire\Tables\Traits\HtmlComponents;
use Netflex\Livewire\Tables\Views\Column;

class ArticleTable extends TableComponent
{
    use HtmlComponents;

    public function query() : Builder
    {
        return Article::query()->ignorePublishingStatus();
    }

    public function columns() : array
    {
        return [
            Column::make('ID')
                ->searchable()
                ->sortable()
                ->excludeFromExport(), // This column is visible to the UI, but not export.
            Column::make('ID')
                ->exportOnly(), // This column is only rendered on export
            Column::make('Image')
                ->format(function(User $article) {
                    return $this->image($article->image, 'presetName', $article->name, ['class' => 'img-fluid']);
                })
                ->excludeFromExport(), // This column is visible to the UI, but not export.
            Column::make('Name') // This columns is visible to both the UI and export, and is rendered the same
                ->searchable()
                ->sortable(),
            Column::make('E-mail', 'email')
                ->searchable()
                ->sortable()
                ->format(function(Article $article) {
                    return $this->mailto($article->email, null, ['target' => '_blank']);
                })
                ->exportFormat(function(User $article) { // This column is visible to both the UI and the export, but is formatted differently to the export via this method.
                    return $article->email;
                }),
            Column::make('Actions')
                ->format(function(User $article) {
                    return view('backend.auth.user.includes.actions', ['article' => $article]);
                })
                ->hideIf(!auth()->user())
                ->excludeFromExport(), // This column is visible to the UI, but not export.
        ];
    }
}
```

#### Customizing Exports

Currently, there are no customization options available. But there is a config item called `exports` where you can define the class to do the rendering. You can use the `\Netflex\Livewire\Tables\Exports\Export` class as a base.

More options will be added in the future, but the built in options should be good for most applications.

### Setting Component Options

There are some frontend framework specific options that can be set.

These have to be set from the `$options` property of your component.

They are done this way instead of the config file that way you can have per-component control over these settings.

```php
protected $options = [
    // The class set on the table
    'classes.table' => 'table table-striped table-bordered',

    // The class set on the table's thead
    'classes.thead' => null,

    // The class set on the table's export dropdown button
    'classes.buttons.export' => 'btn btn-secondary',
    
    // Whether or not the table is wrapped in a `.container-fluid` or not
    'container' => true,
    
    // Whether or not the table is wrapped in a `.table-responsive` or not
    'responsive' => true,
];
```

For this to work you have to pass an associative array of overrides to the `$options` property. The above are the defaults, if you're not changing them then you can leave them out or disregard the property all together.

### Passing Properties

To pass properties from your blade view to your table, you can use the normal Livewire mount method:

```php
<livewire:article-table status="{{ request('status') }}" />
```

```php
protected $status = 'active';

public function mount($status) {
    $this->status = $status;
}
```

## License

Copyright [Apility AS](https://www.apility.no/) &copy; 2021

Licensed under the [MIT License](LICENSE.md).

This software contains work based on the [laravel-livewire-tables](https://github.com/rappasoft/laravel-livewire-tables) package: Copyright &copy; [Anthony Rappa](https://github.com/rappasoft) and [contributors](https://github.com/rappasoft/laravel-livewire-tables/graphs/contributors)
