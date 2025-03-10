<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    public function findById($id)
    {
        return Customer::find($id);
    }

    public function findByDocumentNumber($documentNumber)
    {
        return Customer::where('document_number', $documentNumber)->where('active', 1)->first();
    }

    public function verifyDebt($id, $amount, $cashTransactionAmount)
    {
        $response = ['status' => true];
        $customer = $this->findById($id);
        if (!$cashTransactionAmount) {
            if ($customer->debt <= 0) {
                $response = ['status' => false, 'message' => 'El cliente no tiene deuda.'];
            }
        }
        $customerDebt = $cashTransactionAmount ? $customer->debt + $cashTransactionAmount : $customer->debt;
        if ($customerDebt < $amount) {
            $response = ['status' => false, 'message' => 'El cliente tiene una deuda menor al monto del movimiento. Deuda total: ' . $customerDebt];
        }

        return $response;
    }

    public function updateAvailableBalance($id, $amount, $add)
    {
        $customer = $this->findById($id);
        if ($add) {
            $customer->available_balance += $amount;
        } else {
            $customer->available_balance -= $amount;
        }
        $customer->save();
    }
}
