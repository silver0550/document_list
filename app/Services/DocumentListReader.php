<?php

namespace App\Services;

use App\Enums\DocumentTypesEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class DocumentListReader
{
    public function __invoke(string $filePath): Collection
    {
        $lines = File::lines($filePath);
        $collection = collect();

        foreach ($lines as $line) {
            if (!trim($line) || strpos($line, 'id;document_type;partner;items') === 0) {
                continue;
            }

            $data = str_getcsv($line, ";");
            list($id, $documentType, $partnerJson, $itemsJson) = $data;
            $partner = json_decode($partnerJson, true);
            $items = json_decode($itemsJson, true);

            if (empty($documentType) || empty($partner) || empty($items)) {
                continue;
            }

            $collection->push([
                'id' => $id,
                'document_type' => DocumentTypesEnum::from($documentType),
                'partner' => $partner,
                'items' => $items,
            ]);
        }

        return $collection;
    }
}
