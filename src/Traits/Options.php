<?php

namespace Netflex\Livewire\Tables\Traits;

use Illuminate\Support\Arr;

/**
 * Trait Options.
 */
trait Options
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $optionDefaults = [
        'classes' => [
            'buttons' => [
                'export' => 'btn btn-primary',
            ],
            'table' => 'table table-bordered table-striped',
            'thead' => null,
        ],
        'container' => true,
        'responsive' => true,
    ];

    /**
     * @param $option
     *
     * @return mixed
     */
    public function getOption($option)
    {
        return Arr::dot($this->optionDefaults)[$option] ?? null;
    }

    /**
     * @param  array  $overrides
     */
    protected function setOptions(array $overrides = []): void
    {
        foreach ($overrides as $key => $value) {
            data_set($this->optionDefaults, $key, $value);
        }
    }
}
