<?php

namespace App\Repositories\Interfaces;

interface PromotionRepositoryInterface extends BaseRepositoryInterface
{
    public function findPromotionForProductCatalogue ($condition = []);
}
