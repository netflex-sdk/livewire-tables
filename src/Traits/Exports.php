<?php

namespace Netflex\Livewire\Tables\Traits;

use Exception;

use Netflex\Livewire\Tables\Exceptions\UnsupportedExportFormatException;
use Netflex\Livewire\Tables\Exports\Exporter;

use Netflex\Livewire\Tables\Contracts\Writer as WriterContract;
use Netflex\Livewire\Tables\Contracts\Exporter as ExporterContract;

use Netflex\Livewire\Tables\Exports\Writers\CSV as CSV;
use Netflex\Livewire\Tables\Exports\Writers\XLSX as XLSX;
use Netflex\Livewire\Tables\Exports\Writers\XLS as XLS;
use Netflex\Livewire\Tables\Exports\Writers\PDF as PDF;

/**
 * Trait Exports.
 */
trait Exports
{
    /**
     * @var string
     */
    public $exporter = Exporter::class;

    /**
     * @var string
     */
    public $exportFileName = 'data';

    /**
     * @var array
     */
    public $exports = [];

    /**
     * @var string[]
     */
    public $exportFormats = [
        'csv' => CSV::class,
        'xls' => XLS::class,
        'xlsx' => XLSX::class,
        'pdf' => PDF::class,
    ];

    /**
     * @param $type
     *
     * @return mixed
     * @throws Exception
     */
    public function export($type)
    {
        $type = strtolower($type);

        if (!in_array($type, array_keys($this->exportFormats), true)) {
            throw new UnsupportedExportFormatException(__('This export type is not supported.'));
        }

        if (!in_array($type, array_map('strtolower', $this->exports), true)) {
            throw new UnsupportedExportFormatException(__('This export type is not set on this table component.'));
        }

        $writerClass = $this->exportFormats[$type];
        $exporterClass = $this->exporter;

        /** @var WriterContract */
        $writer = new $writerClass;

        /** @var ExporterContract */
        $exporter = new $exporterClass($this->models(), $this->columns());

        $stream = $exporter->export($writer);
        $filename = $this->exportFileName . $writer->getExtension();

        return response()->streamDownload(function () use ($stream) {
            echo $stream();
        }, $filename);
    }
}
