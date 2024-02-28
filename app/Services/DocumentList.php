<?php

namespace App\Services;

use App\Enums\DocumentTypesEnum;
use App\Exceptions\DocumentumTypeException;
use Illuminate\Support\Collection;
use ValueError;

class DocumentList
{

    private readonly DocumentTypesEnum $documentType;
    private string $path;

    /**
     * @throws DocumentumTypeException
     */
    public function __construct(
        string $documentumType,
        private readonly int $partnerId,
        private readonly int $minPrice,
    ) {
        try {
            $this->documentType = DocumentTypesEnum::from($documentumType);
        } catch (ValueError $e) {

            throw new DocumentumTypeException();
        }
    }

    public function get(bool $string = false): string|Collection
    {
        $data = (new DocumentListReader)($this->path);

        return $string
            ? $this->getFormattedString($this->getFilteredData($data))
            : $this->getFilteredData($data);
    }

    public function fromTestFile(): self
    {
        $this->setPath(storage_path('app/document_list.csv'));

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    protected function getFilteredData(Collection $data): Collection
    {
        return $data->filter(function ($item) {
            $isMatchingPartnerId = $item['partner']['id'] == $this->partnerId;
            $isMatchingDocumentType = $item['document_type'] == $this->documentType;
            $totalPrice = collect($item['items'])->sum(function ($subItem) {
                return $subItem['unit_price'] * $subItem['quantity'];
            });

            return $isMatchingPartnerId && $isMatchingDocumentType && $totalPrice >= $this->minPrice;
        });
    }

    protected function getFormattedString(Collection $documents): string
    {
        $headerFormat = "| %-12s | %-15s | %-20s | %-10s |\n";
        $dataFormat = "| %-12s | %-15s | %-20s | %-10s |\n";
        $line = str_repeat('-', 70)."\n";

        $output = "";

        $output .= sprintf($headerFormat, 'Document ID', 'Document Type', 'Partner Name', 'Total');
        $output .= $line;

        $documents->each(function ($document) use ($dataFormat, $line, &$output) {
            $total = collect($document['items'])->reduce(function ($carry, $item) {

                return $carry + $item['unit_price'] * $item['quantity'];
            }, 0);

            $output .= sprintf($dataFormat,
                $document['id'],
                $document['document_type']->value,
                $document['partner']['name'],
                $total
            );
            $output .= $line;
        });

        return $output;
    }
}
