<?php

namespace App\Enums;

enum DocumentTypesEnum: string
{
    case INVOICE = 'invoice';
    case PROFORMA = 'proforma';
    case RECEIPT = 'receipt';

    public function getReadableText(): string
    {
        return match ($this){
            self::INVOICE => __('document_type.invoice'),
            self::PROFORMA => __('document_type.proforma'),
            self::RECEIPT => __('document_type.receipt'),
        };
    }
}
