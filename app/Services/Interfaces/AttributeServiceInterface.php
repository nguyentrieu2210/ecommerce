<?php

namespace App\Services\Interfaces;

/**
 * Interface AttributeServiceInterface
 * @package App\Services\Interfaces
 */
interface AttributeServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
