<?php

namespace App\Repositories\Salary;

use App\Models\Salary;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class SalaryRepository extends BaseRepository implements \App\Repositories\Salary\SalaryRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return Salary::class;
    }

    public function findSalaryCurrentMonthByEmployeeId($employee_id){
        $thisMonth = Carbon::now('Asia/Ho_Chi_Minh')->format("Y-m");
        return $this->model->where('employee_id', $employee_id)->where('month', $thisMonth)->first();
    }


}
