<?php

namespace Netflex\Livewire\Tables\Exports\Writers;

use PhpOffice\PhpSpreadsheet\Writer\Xls as XlsWriter;

class XLS extends Writer
{
    /**
     * @var string
     */
    protected $type = XlsWriter::class;

    /**
     * @var string
     */
    protected $extension = '.xls';
}
