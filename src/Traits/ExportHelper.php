<?php

namespace Netflex\Livewire\Tables\Traits;

use Illuminate\Support\Str;

/**
 * Trait ExportHelper.
 */
trait ExportHelper
{
    /**
     * @return array
     */
    public function headings(): array
    {
        $headers = [];

        foreach ($this->columns as $column) {
            if ($column->isExportOnly() || ($column->isVisible() && $column->includedInExport())) {
                $headers[] = $column->getText() ? $column->getText() : $column->getAttribute();
            }
        }

        return $headers;
    }
}
