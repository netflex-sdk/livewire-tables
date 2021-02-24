<?php

namespace Netflex\Livewire\Tables\Contracts;

use Closure;
use Netflex\Query\Builder;
use Netflex\Livewire\Tables\Views\Column;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface Exporter
{
    /**
     * @return Builder
     */
    public function query();

    /**
     * @return Column[]
     */
    public function columns();

    /**
     * @return string[]
     */
    public function headings(): array;

    /**
     * @param array $row 
     * @return mixed[]
     */
    public function map($row): array;

    /**
     * @return Writer
     */
    public function export(Writer $writer): Closure;
}
