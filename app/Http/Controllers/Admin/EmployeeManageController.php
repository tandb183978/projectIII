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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeManageController extends Controller
{
    protected static $base_salary = array(
        'Staff' => 10000000,
        'Deputy' => 15000000,
        'Manager' => 20000000,
    );

    protected static  $max_leaves = array(
        'Staff' => 1,
        'Deputy' => 2,
        'Manager' => 3,
    );

    public function getLatestSalaryByEmployeeId($employee_id){
        return Salary::where([['employee_id','=', $employee_id], ['month', '=', $this->curMonth()]])->first();
    }

    public function list_employee(){
        $employees = Employee::all();
        return view('admin.employee.list_employee', [
            'employees' => $employees
        ]);
    }

    public function add_employee(){
        return view('admin.employee.add_employee');
    }

    public function store_employee(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'dob' => 'required|date',
            'email' => 'required|email|unique:users',
            'gender' => 'required',
            'phone' => 'required|regex:/(\d)+/u',
            'department' => 'required',
            'position' => 'required',
            'address' => 'required',
            'country' => 'required',
            'imageprofile' => 'image',
            'password' => 'required|min:8|confirmed'
        ]);
        if ($validator->fails()){
            return back()->withErrors($validator);
        }
        /**
         * Tạo User trước
         */
        $user = User::create([
            'name' => $request->input('firstname').' '.$request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        $roleEmployeeId = Role::where('name','=','employee')->first()->id;
        $user->roles()->attach($roleEmployeeId);
        /**
         *  Tạo employee
         */
        $employee = Employee::create([
            'user_id' => $user->id,
            'first_name' => request('firstname'),
            'last_name' => request('lastname'),
            'birth_day' => request('dob'),
            'gender' => request('gender'),
            'phone' => request('phone'),
            'department' => request('department'),
            'position' => request('position'),
            'address' => request('address'),
            'country' => request('country'),
            'image_profile' => request()->file('imageprofile')->store('public/img'),
            'base_salary' => static::$base_salary[request('position')],
            'max_leaves' => static::$max_leaves[request('position')],
        ]);

        /**
         * Tạo salary tương ứng với employee
         */
        $thisMonth = $this->curMonth();
        $salary = Salary::create([
            'name' => $request->input('firstname').' '.$request->input('lastname'),
            'month' => $thisMonth,
            'employee_id' => $employee->id,
            'subsidy' => null,
            'allowance' => null,
            'insurance' => null,
            'take_home_pay' => null,
        ]);

        /**
         * Tạo info_details_month
         */

        Info_Details_Month::create([
            'salary_id' => $salary->id,
            'month' => $thisMonth,
            'number_day' => Carbon::now()->daysInMonth,
            'number_dayoff' => 0,
            'number_dayon' => 0,
            'number_dayleft' => 0,
            'overtime_workings' => 0,
            'undertime_workings' => 0,
        ]);

        return back()->with('message','Add employee success!');
    }

    public function edit_view($employee_id){
        $employee = Employee::where('id','=',$employee_id)->first();
        return view('admin.employee.edit', compact('employee'));
    }

    public function edit($employee_id){
        if (request('password')) {
            $this->validate(request(), [
                'password' => 'min:8|confirmed',
                'email' => 'email',
                'phone' => 'regex:/(\d)*/u'
            ]);
        }else {
            $this->validate(request(), [
                'password' => 'confirmed',
                'email' => 'email',
                'phone' => 'regex:/(\d)*/u'
            ]);
        }
        $employee = Employee::where('id','=',$employee_id)->first();
        $user = User::where('id',$employee->user_id)->first();

        if (request('firstname') != $employee->first_name)
            $employee->first_name= request('firstname');
        if (request('lastname') != $employee->last_name)
            $employee->last_name = request('lastname');
        if (request('dob'))
            $employee->birth_day = request('dob');
        if (request('email') != $user->email)
            $user->email = request('email');
        if (request('gender'))
            $employee->gender = request('gender');
        if (request('phone') != $employee->phone)
            $employee->phone = request('phone');
        if (request('department'))
            $employee->department = request('department');
        if (request('position'))
            $employee->position = request('position');
        if (request('address') != $employee->address)
            $employee->address = request('address');
        if (request('country') != $employee->country)
            $employee->country = request('country');
        if (request()->file('imageprofile'))
            $employee->image_profile = request()->file('imageprofile')->store('public/img');
        if (request('password'))
            $user->password = Hash::make(request('password'));
        $user->name = $employee->first_name.' '.$employee->last_name;

        /**
         * Update employee
         */

        Employee::find($employee_id)->update([
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'birth_day' => $employee->birth_day,
            'gender' => $employee->gender,
            'phone' => $employee->phone,
            'department' => $employee->department,
            'position' => $employee->position,
            'address' => $employee->address,
            'country' => $employee->country,
            'image_profile' => $employee->image_profile,
            'base_salary' => static::$base_salary[$employee->position],
            'max_leaves' => static::$max_leaves[$employee->position],
        ]);
        /**
         * Update user
         */
        User::where('id',$employee->user_id)->update([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
        ]);
        return back()->with('message','Edit employee success');
    }

    public function delete($employee_id){
        /**
         * Delete attendance:
         */
        Attendance::where('employee_id', $employee_id)->delete();
        /**
         * Delete salary:
         */
        $salaries = Salary::where('employee_id', $employee_id)->get();
        foreach ($salaries as $salary){
            $salary->info_detail()->delete();
            $salary->delete();
        }
        /**
         * Delete leave:
         */
        $leaves = Leave::where('employee_id', $employee_id)->get();
        foreach ($leaves as $leave){
            $leave->format_leave()->delete();
            $leave->delete();
        }
        /**
         * Convert user->DA
         */
        $employee = Employee::where('id', $employee_id)->first();
        $employee->delete();
        $user = User::where('id', $employee->user_id)->first();
        $user->deleted = true;
        $user->name = 'EM user';
        $user->save();
//        $user->roles()->detach();
//        $user->mails()->detach();
//        $user->delete();

        return back();
    }
}
