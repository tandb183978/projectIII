<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Info_Details_Month;
use App\Models\Salary;
use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Leave\LeaveRepository;
use App\Repositories\Salary\SalaryRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $attendanceRepo, $leaveRepo;
    public function __construct(SalaryRepository $salaryRepo, AttendanceRepository $attendanceRepo, LeaveRepository $leaveRepo)
    {
        parent::__construct($salaryRepo);
        $this->attendanceRepo = $attendanceRepo;
        $this->leaveRepo = $leaveRepo;
    }


    public function index(){
        /**
         * Kiểm tra có phải đầu tháng không, nếu phải cập nhật bảng lương cho nhân viên, và tính lương theo attendance
         * tới tháng đó
         */
        /*....*/
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        if ($this->earlyMonthOrNot()){
            $employees = Employee::all();
            $curMonth = $this->curMonth();
            foreach ($employees as $employee){
                /* Tạo bảng lương tháng đó */
                $salary = Salary::create([
                    'name' => $employee->first_name.' '.$employee->last_name,
                    'month' => $curMonth,
                    'employee_id' => $employee->id,
                    'subsidy' => null,
                    'allowance' => null,
                    'insurance' => null,
                    'take_home_pay' => null,
                ]);
                /* Tạo bảng info_detail_month cho tháng này */
                Info_Details_Month::create([
                    'salary_id' => $salary->id,
                    'month' => $curMonth,
                    'number_day' => Carbon::now()->daysInMonth,
                    'number_dayon' => 0,
                    'number_dayoff' => 0,
                    'number_dayleft' => 0,
                    'overtime_workings' => 0,
                    'undertime_workings' => 0,
                ]);

            }
        }
        /**
         * Mỗi ngày tạo mới 1 attendance cho tất cả nhân viên
         */
        $curDate = $this->curDate();
        $attendance = Attendance::where('date', $curDate)->first();
        if (!$attendance){
            $employees = Employee::all();
            foreach ($employees as $employee){
                /* Kiểm tra xem hôm nay nhân viên có được nghỉ phép không? */
                $existLeaveDays = $this->leaveRepo->arrayOfExistDay($employee->id, $this->curMonth(), array('Accepted'));
                if ($existLeaveDays[$this->curDay()]){
                    $this->attendanceRepo->custom_create($employee->id, $curDate, null, null, 'Left (allowed)', '00:00:00' );
                    $this->update_info_details_month($employee->id, 0, 0, 0, 0, 1);
                } else {
                    $this->attendanceRepo->custom_create($employee->id, $curDate);
                    $this->update_info_details_month($employee->id, 0, 8, 1, 0, 0);
                }

            }
        }

        return view('admin.index');
    }
}
