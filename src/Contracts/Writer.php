<?php

namespace Netflex\Livewire\Tables\Contracts;

use Closure;
use Netflex\Livewire\Tables\Contracts\Exporter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface Writer
{
    public function getType(): string;

    /**
     * @param Exporter $exporter
     * @return Closure
     */
    public function write(Spreadsheet $spreadsheet): Closure;

    /**
     * @return string 
     */
    public function getExtension(): string;
}
