<?php

namespace App\Services\Interfaces;

/**
 * Interface UserCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface UserCatalogueServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function permission ($payload);
    public function destroy($id);
}
