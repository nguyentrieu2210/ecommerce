<?php

namespace App\Services\Interfaces;

/**
 * Interface WarehouseServiceInterface
 * @package App\Services\Interfaces
 */
interface WarehouseServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
