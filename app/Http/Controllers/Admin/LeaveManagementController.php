<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Models\Info_Details_Month;
use App\Models\Leave;
use App\Models\Role;
use App\Models\Salary;
use App\Models\User;
use App\Models\Employee;

use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveManagementController extends Controller
{
    public function list_leaves()
    {
        $all = false;
        $month = false;
        $title_get_by_session = session()->get('title');
        if ($title_get_by_session) {
            if ($title_get_by_session == 'all') {
                $all = $title_get_by_session;
            } else if ($title_get_by_session == ' this month') {
                $all = false;
                $month = false;
            } else {
                $month = explode('-', $title_get_by_session)[1] . '-' . explode('-', $title_get_by_session)[0];
            }
        }
        if (request('all'))
            $all = true;
        if (request('month'))
            $month = request('month');
        /* Get leave of this employee */
        if ($all) {
            $title = 'all';
            $list_leaves = Leave::with('employee')->get();
        } else if ($month) {
            $title = explode('-', $month)[1] . '-' . explode('-', $month)[0];
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
            $list_leaves = Leave::with('employee')->where('month', Carbon::parse($month)->format("Y-m"))->get();
        } else {
            $title = ' this month';
            $month = $this->curMonth();
            $list_leaves = Leave::with('employee')->where('month', $month)->get();
        }

        $notSeen_leaves = array();
        $declined_leaves = array();
        $accepted_leaves = array();

        foreach ($list_leaves as $leave) {
            if ($leave->status == 'Not seen') {
                array_push($notSeen_leaves, $leave);
            } elseif ($leave->status == 'Declined') {
                array_push($declined_leaves, $leave);
            } elseif ($leave->status == 'Accepted') {
                array_push($accepted_leaves, $leave);
            }
        }

        $data = [
            'title' => $title,
            'not_seen' => $notSeen_leaves,
            'declined' => $declined_leaves,
            'accepted' => $accepted_leaves,
        ];

        return view('admin.leave.list_leaves')->with($data);
    }

    public function leave_detail($leave_id){
        $leave = Leave::with('employee', 'format_leave')->where('id', $leave_id)->first();
        $employee = Employee::with('user')->where('id', $leave->employee_id)->first();
        $data = [
            'name' => $employee->user()->first()->name,
            'format' => $leave->format_leave()->first()->format,
            'leave' => $leave,
            'message' => session('message'),
        ];
        return view('admin.leave.leave_detail')->with($data);
    }

    public function leave_process($leave_id){
        $leave = Leave::with('employee', 'format_leave')->where('id', $leave_id)->first();
        if (request('declined')){
            $leave->status = 'Declined';
            $leave->save();
        }else if (request('accepted')) {
            $leave->status = 'Accepted';
            $leave->save();

//            /**
//             * Tính thêm vào số ngày nghỉ, nếu là ngày hôm nay
//             */
//            $employee = $leave->employee()->first();
//            $info_detail_month = Salary::where('employee_id', $employee->id)->where('month', $leave->month)->first()->info_detail()->first();
//            $info_detail_month->number_dayleft += 1;
//            $info_detail_month->save();
//

        }else {
            /* Dont do anything */
            $x = 1;
        }

        return redirect()->route('admin.leave.leave_detail', $leave_id)->with(['message' => $leave->status]);
    }
}
