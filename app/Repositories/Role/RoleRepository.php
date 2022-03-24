<?php

namespace App\Repositories\Role;


use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements \App\Repositories\Role\RoleRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return Role::class;
    }

}
