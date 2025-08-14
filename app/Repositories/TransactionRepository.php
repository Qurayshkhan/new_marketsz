<?php

namespace App\Repositories;

use App\Interfaces\TransactionInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionInterface
{
    protected $transaction;
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function getAllTransaction()
    {
        return $this->transaction->with('user')->paginate(25);
    }

    public function create($data)
    {
        return $this->transaction->create($data);
    }

    public function getTransactionById($userId)
    {
        return $this->transaction->where('user_id', $userId)->with('user')->paginate(25);
    }

    public function findById($transactionId)
    {
        return $this->transaction->findOrFail($transactionId);
    }

    public function update($id, $data)
    {
        return $this->transaction->where('id', $id)->update($data);
    }

    public function sumTotalTransaction()
    {
        return $this->transaction->sum('amount');
    }
}
