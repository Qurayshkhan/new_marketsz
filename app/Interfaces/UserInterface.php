<?php


namespace App\Interfaces;

interface UserInterface
{
    public function customers();
    public function findById($userId);
    public function updateUser($userId, $data);


}
