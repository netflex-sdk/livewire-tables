<?php

namespace Netflex\Livewire\Tables\Exports;

use Closure;

use Netflex\Query\Builder;
use Netflex\Livewire\Tables\Contracts\Exporter as ExporterContract;
use Netflex\Livewire\Tables\Views\Column;
use Netflex\Livewire\Tables\Contracts\Writer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Netflex\Livewire\Tables\Traits\ExportHelper;

/**
 * Class CSVExport.
 */
class Exporter implements ExporterContract
{
    use ExportHelper;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var Column[]
     */
    protected $columns;

    public function __construct(Builder $builder, array $columns)
    {
        $this->builder = $builder;
        $this->columns = $columns;
    }

    public function query()
    {
        return $this->builder;
    }

    public function columns()
    {
        return $this->columns;
    }

    public function map($row): array
    {
        $map = [];

        foreach ($this->columns as $column) {
            /** @var Column */
            $column = $column;

            if ($column->isExportOnly() || ($column->isVisible() && $column->includedInExport())) {
                if ($column->isFormatted()) {
                    if ($column->hasExportFormat()) {
                        $map[] = $column->formattedForExport($row, $column);
                    } else {
                        $map[] = strip_tags($column->formatted($row, $column));
                    }
                } else {
                    $map[] = data_get($row, $column->getAttribute());
                }
            }
        }

        return $map;
    }

    public function export(Writer $writer): Closure
    {
        $spreadsheet = new Spreadsheet;

        $sheet = $spreadsheet->getActiveSheet();

        $rowIterator = $sheet->getRowIterator();
        $cellIterator = $rowIterator->current()->getCellIterator();

        foreach ($this->headings() as $heading) {
            $cellIterator->current()->setValue($heading);
            $cellIterator->next();
        }

        $rowIterator->next();

        $models = $this->query()->paginate();
        $columns = $this->columns();

        do {
            foreach ($models->data as $item) {
                $cellIterator = $rowIterator->current()->getCellIterator();
                $columns = $this->map($item);

                foreach ($columns as $column) {
                    $cellIterator->current()->setValue($column);
                    $cellIterator->next();
                }

                $rowIterator->next();
            }
        } while ($models = $models->next());

        return $writer->write($spreadsheet);
    }
}
