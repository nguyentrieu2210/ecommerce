<?php

namespace App\Services\Interfaces;

/**
 * Interface SupplierServiceInterface
 * @package App\Services\Interfaces
 */
interface SupplierServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
