<?php
namespace App\Repositories\Salary;

interface SalaryRepositoryInterface
{
    public function findSalaryCurrentMonthByEmployeeId($employee_id);

}
