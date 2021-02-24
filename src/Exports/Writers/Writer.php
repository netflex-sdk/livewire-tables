<?php

namespace Netflex\Livewire\Tables\Exports\Writers;

use Closure;

use Netflex\Livewire\Tables\Contracts\Writer as WriterContract;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter as BaseWriterContract;

abstract class Writer implements WriterContract
{
    /**
     * @var string
     */
    protected $type = BaseWriterContract::class;

    /**
     * @var string
     */
    protected $extension = '';

    /**
     * @return string 
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * 
     * @param Spreadsheet $spreadsheet 
     * @return Closure
     */
    public function write(Spreadsheet $spreadsheet): Closure
    {
        return function () use ($spreadsheet) {
            $class = $this->type;
            $handle = tmpfile();

            /** @var BaseWriterContract */
            $writer = new $class($spreadsheet);

            $writer->save($handle);

            rewind($handle);

            $content = '';

            while (!feof($handle)) {
                $content .= fgets($handle);
            }

            fclose($handle);

            return $content;
        };
    }
}
