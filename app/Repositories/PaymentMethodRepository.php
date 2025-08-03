<?php

namespace App\Repositories;

use App\Interfaces\PaymentMethodInterface;
use App\Models\UserCard;

class PaymentMethodRepository implements PaymentMethodInterface
{
    protected $userCard;
    public function __construct(UserCard $userCard)
    {
        $this->userCard = $userCard;
    }

    public function getCardsByUser($userId)
    {
        return $this->userCard->where('user_id', $userId)->get();
    }

    public function storeUserCard($data)
    {
        return $this->userCard->create($data);
    }

    public function setDefaultCard($id, $userId)
    {
        $this->userCard->where('user_id', $userId)->update(['is_default' => false]);
        return $this->userCard->where('id', $id)->where('user_id', $userId)->update(['is_default' => true]);
    }

    public function deleteCard($id, $userId)
    {
        $card = $this->userCard->where('id', $id)->where('user_id', $userId)->firstOrFail();
        return $card->delete();
    }

    public function findById($id)
    {
        return $this->userCard->where('id', $id)->firstOrFail();
    }

    public function updateUserCard($data, $id)
    {
        return $this->userCard->where('id', $id)->update($data);
    }
}
