<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductCatalogueServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
