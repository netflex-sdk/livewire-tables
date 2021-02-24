<?php

namespace Netflex\Livewire\Tables\Exports\Writers;

use PhpOffice\PhpSpreadsheet\Writer\Csv as CsvWriter;

class CSV extends Writer
{
    /**
     * @var string
     */
    protected $type = CsvWriter::class;

    /**
     * @var string
     */
    protected $extension = '.csv';
}
