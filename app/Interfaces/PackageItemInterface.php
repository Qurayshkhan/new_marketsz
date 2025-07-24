<?php


namespace App\Interfaces;

interface PackageItemInterface
{
    public function store($data);
    public function insert($items, $package);
}
