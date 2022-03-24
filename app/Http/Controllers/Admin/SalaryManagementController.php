<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Info_Details_Month;
use App\Models\Role;
use App\Models\Salary;
use App\Models\User;
use App\Models\Employee;

use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Void_;

class SalaryManagementController extends Controller
{
    public function list_attendance_employees($start_date=null, $end_date=null){
        if ($start_date and $end_date) {
//            Raw select eloquent
//            $start_date = Carbon::parse($start_date.' 00:00:00')->format("Y-m-d H:i:s");
//            $end_date = Carbon::parse($end_date.' 23:59:59')->format("Y-m-d H:i:s");
//            $attendances = DB::select('
//                select * from attendances
//                where created_at >= ? and created_at <= ?
//                order by id desc
//               ', [$start_date, $end_date]);
            $attendances = Attendance::with('employee')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->orderby('date', 'desc')->get();
            $get_all = false;
        } else {
            $attendances = Attendance::with('employee')->orderBy('date', 'desc')->get();
            $get_all = true;
        }

        $names = array();
        $departments= array();
        $positions = array();
        $status = array();

        foreach ($attendances as $index=> $attendance){
            $respect_employee = $attendance->employee()->first();
            $name = $respect_employee->user->name;

            array_push($names, $name);
            array_push($departments, $respect_employee->department);
            array_push($positions, $respect_employee->position);
            array_push($status, $attendance->status);
        }
        $data = [
            'get_all' => $get_all,
            'start_date' => Carbon::parse($start_date)->format("d-m-Y"),
            'end_date' => Carbon::parse($end_date)->format("d-m-Y"),
            'names' => $names,
            'positions' => $positions,
            'departments' => $departments,
            'attendances' => $attendances,
            'status' => $status,
        ];

        return view('admin.salary.list_attendance_employee')->with($data);
    }

    public function list_attendance_range(Request  $request){
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|before_or_equal:today',
            'end_date' => 'required|date|before_or_equal:today|after_or_equal:start_date'
        ]);
        if ($validator->fails())
            return back()->withErrors($validator);
        return self::list_attendance_employees(request('start_date'), request('end_date'));
    }

    public function list_salary_employees(){
                /* Check get all or month by: title and request*/

        $all = false; $month = false;
        $title_get_by_session = session()->get('title');
        if ($title_get_by_session){
            if ($title_get_by_session == 'all'){
                $all = $title_get_by_session;
            } else if ($title_get_by_session == ' this month'){
                $all = false;
                $month = false;
            } else {
                $month = explode('-',$title_get_by_session)[1].'-'.explode('-',$title_get_by_session)[0];
            }
        };
        if (request('all'))
            $all = true;
        if (request('month'))
            $month = request('month');
                /* Get salary */

        if ($all) {
            $title = 'all';
            $list_salary = Salary::with('info_detail', 'employee')->get();
        } else if ($month) {
            $title = explode('-',$month)[1].'-'.explode('-',$month)[0];
            $thisMonth = $this->curMonth();
            if (request('month')) {
                $this->validate(request(), [
                    'month' => ['required', function ($attribute, $value, $fail) use ($thisMonth) {
                        if ($value > $thisMonth) {
                            $fail('The ' . $attribute . ' must before or equal this month');
                        }
                    }]
                ]);
            }
            $list_salary = Salary::with('info_detail', 'employee')->where('month', '=', Carbon::parse($month)->format("Y-m"))->get();
        } else {
            $title = ' this month';
            $month = $this->curMonth();
            $list_salary = Salary::with('info_detail')->where('month',$month)->get();
        }

        $list_employee = array();
        $list_info_detail = array();
        foreach ($list_salary as $salary) {
            array_push($list_employee, $salary->employee()->first());
            array_push($list_info_detail, $salary->info_detail()->first());
        }
        $data = [
            'title' => $title,
            'list_employee' => $list_employee,
            'list_salary' => $list_salary,
            'list_info_detail' => $list_info_detail,
            'status_calculate' => session()->get('status_calculate'),
            'name' => session()->get('name'),
        ];

        return view('admin.salary.list_salary_employee')->with($data);
    }

    public function update_fees($salary_id, $title){
        $salary = Salary::with('employee')->where('id', $salary_id)->first();
        $employee = $salary->employee()->first();
        $data = [
            'name' => $employee->first_name.' '.$employee->last_name,
            'month' => $salary->month,
            'salary_id' => $salary_id,
            'title' => $title
        ];
        return view('admin.salary.update_fees')->with($data);
    }

    public function store_fees($salary_id, $title){
        $validator = Validator::make(request()->all(), [
            'subsidy' => ['required' , function($attribute, $value, $fail) {
                if (is_numeric($value)==false){
                    $fail('The '.$attribute.' must be numeric');
                } else if ($value < 0) {
                    $fail('The '.$attribute.' must be non-negative');
                }
            }],
            'allowance' => ['required' , function($attribute, $value, $fail) {
                if (is_numeric($value)==false){
                    $fail('The '.$attribute.' must be numeric');
                } else if ($value < 0) {
                    $fail('The '.$attribute.' must be non-negative');
                }
            }],
            'insurance' => ['required' , function($attribute, $value, $fail) {
                if (is_numeric($value)==false){
                    $fail('The '.$attribute.' must be numeric');
                } else if ($value < 0) {
                    $fail('The '.$attribute.' must be non-negative');
                }
            }]
        ]);
        if ($validator->fails()){
            return back()->withErrors($validator);
        }
        $salary = Salary::where('id', $salary_id)->first();
        if ($salary) {
            $salary->subsidy = request('subsidy');
            $salary->allowance = request('allowance');
            $salary->insurance = request('insurance');
            $salary->save();
        }
        return redirect()->route('admin.salary.employee_salary')->with(['title' => $title]);
    }

    public function calculate_individual_salary($salary_id, $title, $return=true)
    {
        $salary = Salary::where('id', $salary_id)->first();
        $info_detail = $salary->info_detail()->first();
        $employee = $salary->employee()->first();
        $subsidy = $salary->subsidy;
        $allowance = $salary->allowance;
        $insurance = $salary->insurance;
        if (!$subsidy or !$allowance or !$insurance){
            $name = $employee->first_name.' '.$employee->last_name;
            if ($return == false)
                return ['name' => $name, 'status_calculate'=>'false'];
            return redirect()->route('admin.salary.employee_salary')->with(['status_calculate'=>'false', 'title'=>$title, 'name' => $name]);
        } else {
            $base_salary = $employee->base_salary;
            $max_leaves = $employee->max_leaves;

            $day = $info_detail->number_day;
            $dayoff = $info_detail->number_dayoff;
            $day_left = $info_detail->number_dayleft;
            $dayon = $info_detail->number_dayon;
            $overtime_hours = $info_detail->overtime_workings;
            $undertime_hours = $info_detail->undertime_workings;
            /**
             * Công thức tính lương:
             */
            $salary->take_home_pay = round($base_salary + ($subsidy+$allowance)*($dayon/$day) - $insurance - abs(min($max_leaves-$day_left, 0))*$base_salary/$day
                                                  + ($overtime_hours-$undertime_hours)*($base_salary/$day/24), 2);
            $salary->save();
        }

        return redirect()->route('admin.salary.employee_salary')->with(['status_calculate'=>'true', 'title'=>$title]);

    }

    public function calculate_all_salary($title)
    {

        if ($title == 'all'){
            $salaries = Salary::all();
        } else if ($title == ' this month'){
            $cur_month = $this->curMonth();
            $salaries = Salary::where('month', $cur_month)->get();
        } else if ($title) {
            $month = explode('-',$title)[1].'-'.explode('-',$title)[0];
            $salaries = Salary::where('month', $month)->get();
        }
        $fail = '0';
        foreach ($salaries as $salary){
            if (!$salary->take_home_pay) {
                $fail = self::calculate_individual_salary($salary->id, $title, false);
                if (gettype($fail) == 'array')
                    break;
            }
        }
        if (gettype($fail) == 'array')
            return redirect()->route('admin.salary.employee_salary')->with(['status_calculate'=>'false', 'title'=>$title, 'name' => $fail['name']]);
        return redirect()->route('admin.salary.employee_salary')->with(['status_calculate'=>'true', 'title'=>$title]);
    }



}
