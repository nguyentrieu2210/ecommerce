<?php

namespace App\Services\Interfaces;

/**
 * Interface PurchaseOrderServiceInterface
 * @package App\Services\Interfaces
 */
interface PurchaseOrderServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function confirm ($id, $payload);
    public function cancel ($id, $payload);
}
