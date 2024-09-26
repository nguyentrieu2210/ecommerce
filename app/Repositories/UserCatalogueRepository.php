<?php
    namespace App\Repositories;

    use App\Models\UserCatalogue;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface;

    class UserCatalogueRepository extends BaseRepository implements UserCatalogueRepositoryInterface
    {
        protected $model;
    
        public function __construct(UserCatalogue $model)
        {
            $this->model = $model;
        }
    
    }
    