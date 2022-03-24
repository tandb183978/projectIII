<?php

namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;


class AttendanceController extends Controller
{

    public function create(){
        $employee = Auth::user()->employee()->first();
        $attendance = Attendance::where('employee_id', $employee->id)->orderBy('created_at', 'desc')->first();
        $data = [
            'employee' => $employee,
            'attendance' => $attendance,
            'status' => $attendance->status,
        ];
        return view('employee.attendance.create')->with($data);
    }

    public function store_entry($attendance_id){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $employee = Auth::user()->employee()->first();
        $attendance = Attendance::where('id', $attendance_id)->update([
            'entry_time' => date("Y-m-d H:i:s"),
            'status' => 'Working'
        ]);

        $data = [
            'employee' => $employee,
            'attendance' => $attendance,
            'status' => 'Working',
        ];
        return back()->with($data);
    }

    /* Update exit */

    public function store_exit($attendance_id){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $attendance = Attendance::find($attendance_id);
        $attendance->exit_time = date("Y-m-d H:i:s");
        $attendance->status = 'Present';

        $interval = date_diff(date_create($attendance->entry_time), date_create($attendance->exit_time));
        $time = "".$interval->format("%h").":".$interval->format("%i").":".$interval->format("%s");
        $attendance->working_hours = date("H:i:s", strtotime($time));

        $attendance->save();

        $dec_time = $this->time_to_decimal($attendance->working_hours);
        $overtime_hours = ($dec_time>8)?($dec_time-8):0;
        $undertime_hours = (($dec_time<8)?(8-$dec_time):0) - 8;
        $this->update_info_details_month($attendance->employee_id, $overtime_hours, $undertime_hours, -1, 1 );
        $data = [
            'employee' => Auth::user()->employee()->first(),
            'attendance' => $attendance,
            'status' => 'Present',
        ];

        return back()->with($data);
    }

    public function list(){
        $employee = Auth::user()->employee()->first();
        $attendance_list = Attendance::where('employee_id', $employee->id)->orderby('date', 'desc')->get();

        $data = [
            'attendance_list' => $attendance_list,
            'employeeid' => $employee->id,
        ];
        return view('employee.attendance.attendance_list')->with($data);
    }

}
