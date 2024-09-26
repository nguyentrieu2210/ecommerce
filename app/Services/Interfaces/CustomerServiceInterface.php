<?php

namespace App\Services\Interfaces;

/**
 * Interface CustomerServiceInterface
 * @package App\Services\Interfaces
 */
interface CustomerServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
