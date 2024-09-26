<?php

namespace App\Services\Interfaces;

/**
 * Interface SourceServiceInterface
 * @package App\Services\Interfaces
 */
interface SourceServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
