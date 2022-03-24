<?php

namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Salary;
use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Salary\SalaryRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    protected $attendanceRepo;
    public function __construct(SalaryRepository $salaryRepo, AttendanceRepository  $attendanceRepo)
    {
        parent::__construct($salaryRepo);
        $this->attendanceRepo = $attendanceRepo;
    }

    public function convert_to_money_str($str): string
    {
        $len = strlen($str);
        $i = $len-1;
        $money_str = '';
        for ($i = 0; $i < $len; $i++){
            $money_str .= $str[$i];
            if ((($len %3 == 0 and $i % 3 == 2) or ($len %3 == 1 and $i %3 == 0) or ($len %3 == 2 and $i%3 == 1)) and ($i != $len-1))
                $money_str .= '.';
        }
        return $money_str;
    }

    public function index(){
        /**
         * Tạo attendance cho ngày hôm nay (nếu chưa tồn tại)
         */
        $employee_id = Auth::user()->employee()->first()->id;
        $curDate = $this->curDate();
        $attendance = Attendance::where('employee_id', $employee_id)->where('date', $curDate)->first();
        if (!$attendance){
            $this->attendanceRepo->custom_create($employee_id, $curDate);
            $this->update_info_details_month($employee_id, 0, 8, 1, 0, 0);
        }

        $user = Auth::user();
        $employee = $user->employee()->first();
        $salaries = Salary::where('employee_id', $employee->id)->orderBy('month', 'DESC')->get();
        $pre_salary = 0;
        $base_salary = $employee->base_salary;
        foreach ($salaries as $salary){
            if ($salary->take_home_pay) {
                $pre_salary = round($salary->take_home_pay, 0);
                break;
            }
        }
        $pre_salary_str = strval($pre_salary);
        $base_salary_str = strval($base_salary);

        $data = [
            'user' => $user,
            'employee' => $employee,
            'pre_salary' => $this->convert_to_money_str(strval($pre_salary)),
            'base_salary' => $this->convert_to_money_str(strval($base_salary)),
        ];
        return view('employee.index')->with($data);
    }

    public function change_information(){

        return back();
    }
}
