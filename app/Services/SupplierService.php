<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function findByDocumentNumber($documentNumber)
    {
        return Supplier::where('document_number', $documentNumber)->where('active', 1)->first();
    }
}
