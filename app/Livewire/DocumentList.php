<?php

namespace App\Livewire;

use App\Exceptions\DocumentumTypeException;
use Illuminate\View\View;
use Livewire\Component;
use App\Services\DocumentList as DocList;

class DocumentList extends Component
{

    public array $documents;
    public ?int $partnerId = null;
    public ?int $minPrice = null;
    public ?string $documentType = 'invoice';

    /**
     * @throws DocumentumTypeException
     */
    public function mount(): void
    {
        $this->documents = (new DocList('invoice', 354, 1))
            ->fromTestFile()
            ->get(all:true)
            ->toArray();
    }
    public function render(): View
    {
        return view('livewire.document-list')->layout('components.layouts.base');
    }

    /**
     * @throws DocumentumTypeException
     */
    public function updated($property)
    {
        if($property != 'documents' && ( $this->documentType && $this->minPrice && $this->partnerId)){
            $this->documents =  (new DocList($this->documentType, $this->partnerId, $this->minPrice))
                ->fromTestFile()
                ->get()
                ->toArray();
        }
    }
}
