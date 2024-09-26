<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeCatalogueServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
