<?php

namespace Netflex\Livewire\Tables\Exports\Writers;

use Closure;

use Netflex\API\Facades\API;

use GuzzleHttp\Client;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Html as HtmlWriter;

class PDF extends Writer
{
    /**
     * @var string
     */
    protected $type = HtmlWriter::class;

    /**
     * @var string
     */
    protected $extension = '.pdf';

    /**
     * 
     * @param Spreadsheet $spreadsheet 
     * @return Closure
     */
    public function write(Spreadsheet $spreadsheet): Closure
    {
        return function () use ($spreadsheet) {
            /** @var Client */
            $client = API::getGuzzleInstance();

            $request = $client->post('foundation/pdf', ['json' => [
                'fetch' => true,
                'url' => 'data:text/html;base64,' . base64_encode(parent::write($spreadsheet)())
            ]]);

            return (string) $request->getBody();
        };
    }
}
