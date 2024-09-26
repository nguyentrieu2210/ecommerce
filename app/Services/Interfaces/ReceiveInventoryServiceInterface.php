<?php

namespace App\Services\Interfaces;

/**
 * Interface ReceiveInventoryServiceInterface
 * @package App\Services\Interfaces
 */
interface ReceiveInventoryServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function received ($id, $payload);
    public function paid ($id, $payload);
}
