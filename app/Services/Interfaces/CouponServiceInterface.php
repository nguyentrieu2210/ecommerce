<?php

namespace App\Services\Interfaces;

/**
 * Interface CouponServiceInterface
 * @package App\Services\Interfaces
 */
interface CouponServiceInterface
{
    public function paginate();
    public function store ($payload = []);
    public function update ($id, $payload);
    public function destroy($id);
}
