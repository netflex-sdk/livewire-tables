<?php

namespace Netflex\Livewire\Tables\Exports\Writers;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class XLSX extends Writer
{
    /**
     * @var string
     */
    protected $type = XlsxWriter::class;

    /**
     * @var string
     */
    protected $extension = '.xlsx';
}
