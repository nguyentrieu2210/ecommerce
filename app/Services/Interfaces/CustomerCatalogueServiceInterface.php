<?php

namespace App\Services\Interfaces;

/**
 * Interface CustomerCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerCatalogueServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function permission ($payload);
    public function destroy($id);
}
