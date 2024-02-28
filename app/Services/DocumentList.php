<?php

namespace App\Services;

use App\Enums\DocumentTypesEnum;
use App\Exceptions\DocumentumTypeException;
use Illuminate\Support\Collection;
use ValueError;

class DocumentList
{

    private readonly DocumentTypesEnum $documentType;
    private readonly int $partnerId;
    private readonly int $minPrice;
    private string $path;

    /**
     * @throws DocumentumTypeException
     */
    public function __construct(
        string $documentumType,
        int $partnerId,
        int $minPrice,
    ) {
        try {
            $this->documentType = DocumentTypesEnum::from($documentumType);
        } catch (ValueError $e) {

            throw new DocumentumTypeException(__('error.documentum_type'));
        }

        $partnerId < 1
            ? throw new DocumentumTypeException(__('error.partner_id'))
            : $this->partnerId = $partnerId;

        $minPrice < 1
            ? throw new DocumentumTypeException(__('error.minimum_price'))
            : $this->minPrice = $minPrice;


    }

    public function get(bool $string = false, bool $all = false): string|Collection
    {
        $data = (new DocumentListReader)($this->path);

        if ($all) {
            return $string
                ? $this->getFormattedString($data)
                : $this->getFormattedData($data);
        }

        return $string
            ? $this->getFormattedString($this->getFilteredData($data))
            : $this->getFormattedData($this->getFilteredData($data));
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

    protected function getFormattedData(Collection $documents): Collection
    {
        return $documents->map(function ($document) {
            $total = collect($document['items'])->reduce(function ($carry, $item) {
                return $carry + $item['unit_price'] * $item['quantity'];
            }, 0);

            return [
                'id' => $document['id'],
                'documentum_type' => $document['document_type']->value,
                'partner_name' => $document['partner']['name'],
                'total' => $total,
            ];
        });
    }
}
