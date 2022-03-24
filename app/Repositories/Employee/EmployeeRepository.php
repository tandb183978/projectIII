<?php

namespace App\Repositories\Employee;

use App\Models\Employee;
use App\Repositories\BaseRepository;

class EmployeeRepository extends BaseRepository implements \App\Repositories\Employee\EmployeeRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return Employee::class;
    }


}
