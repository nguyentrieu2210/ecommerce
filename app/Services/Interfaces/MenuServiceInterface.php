<?php

namespace App\Services\Interfaces;

/**
 * Interface MenuServiceInterface
 * @package App\Services\Interfaces
 */
interface MenuServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
