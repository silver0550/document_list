<?php

namespace App\Console\Commands;

use App\Exceptions\DocumentumTypeException;
use App\Services\DocumentList;
use Illuminate\Console\Command;

class DocumentListCommand extends Command
{

   protected $signature = 'document_list {documentType} {partnerId} {minPrice}';
    protected $description = 'Displays a list of documents based on provided arguments';

    /**
     * @throws DocumentumTypeException
     */
    public function handle(): int
    {
        $documentType = $this->argument('documentType');
        $partnerId = $this->argument('partnerId');
        $minPrice = $this->argument('minPrice');

        if (!$documentType || !$partnerId || !$minPrice) {
            $this->error('All three arguments are required.');

            return self::FAILURE;
        }

        $this->line(
            (new DocumentList($documentType,$partnerId, $minPrice))
                ->fromTestFile()
                ->get(true)
        );

        return self::SUCCESS;
    }
}
