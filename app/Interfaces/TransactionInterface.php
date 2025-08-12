<?php

namespace App\Interfaces;

interface TransactionInterface
{
    public function create($data);

    public function getTransactionById($userId);

    public function findById($transactionId);

    public function update($id, $data);
}
