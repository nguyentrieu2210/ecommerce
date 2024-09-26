<?php

namespace App\Services\Interfaces;

/**
 * Interface PostCatalogueServiceInterface
 * @package App\Services\Interfaces
 */
interface PostCatalogueServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
