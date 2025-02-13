<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function findByDocumentNumber($documentNumber)
    {
        return Customer::where('document_number', $documentNumber)->where('active', 1)->first();
    }
}
