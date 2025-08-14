<?php


namespace App\Interfaces;

interface UserInterface
{
    public function customers();
    public function findById($userId);
    public function update($userId, $data);
    public function users();
    public function store($data);

    public function userCount();

}
