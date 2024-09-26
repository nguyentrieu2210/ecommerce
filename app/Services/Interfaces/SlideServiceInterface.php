<?php

namespace App\Services\Interfaces;

/**
 * Interface SlideServiceInterface
 * @package App\Services\Interfaces
 */
interface SlideServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
