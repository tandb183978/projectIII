<?php

namespace App\Http\Controllers\Employee;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Format_Leave;
use App\Models\Leave;
use App\Models\Salary;
use App\Repositories\Leave\LeaveRepository;
use App\Repositories\Salary\SalaryRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    protected $leaveRepo;
    public function __construct(SalaryRepository $salaryRepo, LeaveRepository $leaveRepo)
    {
        parent::__construct($salaryRepo);
        $this->leaveRepo = $leaveRepo;
    }

    public function getDay($date): int
    {
        return (int)(Carbon::parse($date)->format("d"));
    }

    public function endOfMonth(): string
    {
        return Carbon::now('Asia/Ho_Chi_Minh')->endOfMonth()->format("Y-m-d");
    }

    public function create_form(){
        return view('employee.leave.create_form');
    }

    public function store_form(){
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        $existDates = $this->leaveRepo->arrayOfExistDay($employee->id, $this->curMonth());
        $this->validate(request(), [
            'multipledays' => 'required'
        ]);
        if (request('multipledays') == 'Yes'){
            $validator = Validator::make(request()->all(), [
                'reason' => 'required|min:5|max:50',
                'description' => 'required|min:20|max:1500',
                'start_leave_day' => ['date', 'after_Or_Equal:today', function($attribute, $value, $fail){
                    if ($value > $this->endOfMonth()){
                        $fail('The '.$attribute.' must in this month');
                    }
                }],
                'end_leave_day' => ['date', 'after_Or_Equal:start_leave_day', function($attribute, $value, $fail) use($existDates){
                    if ($value > $this->endOfMonth()){
                        $fail('The '.$attribute.' must in this month');
                    }
                    for ($i = $this->getDay(request('start_leave_day')); $i <= $this->getDay(request('end_leave_day')); $i++){
                        if ($existDates[$i]){
                            $fail('You have register leave for day '.$i);
                        }
                    }
                }],
            ]);
        } else {
            $validator = Validator::make(request()->all(), [
                'reason' => 'required|min:5|max:50',
                'description' => 'required|min:20|max:1500',
                'leave_day' => ['date', 'after_Or_Equal:today', function($attribute, $value, $fail) use($existDates) {
                    if ($value > $this->endOfMonth()){
                        $fail('The '.$attribute.' must in this month');
                    }
                    if ($existDates[$this->getDay(request('leave_day'))]){
                        $fail('You have register leave for this day!');
                    }
                }],
            ]);
        }

        if ($validator->fails()){
            return back()->withErrors($validator)->with(['multipleDay' => request('multipledays')]);
        }

        $leave = [
            'employee_id' => $employee->id,
            'reason' => request('reason'),
            'description' => request('description'),
            'multidays' => request('multipledays'),
            'leave_day' => null,
            'start_leave_day' => null,
            'end_leave_day' => null,
            'status' => 'Not seen',
            'month' => $this->curMonth(),
            'toAdmin' => request('toAdmin'),
            'number_day' => null,
        ];
        if ($leave['multidays'] == 'Yes'){
            $leave['start_leave_day'] = request('start_leave_day');
            $leave['end_leave_day'] = request('end_leave_day');
            $leave['number_day'] = 1+ (int) (Carbon::parse(request('end_leave_day'))->format("d")) - (int) (Carbon::parse(request('start_leave_day'))->format("d"));
        } else {
            $leave['leave_day'] = request('leave_day');
            $leave['number_day'] = 1;
        }

        $created_leave = Leave::create($leave);
        /**
         * Create format :))
         */
        $digit_to_char = array(
            1 => 'one day',
            2 => 'two days',
            3 => 'three days',
            4 => 'four days',
            5 => 'five days',
            6 => 'six days',
            7 => 'one week',
        );

        $my_format = '<div style="padding-top: 20px" class="card-header bg-outline-secondary">'.'<div class="card-title"' .'<h1 style="font-size: 20px"> Dear <span style="color: orangered; font-weight: bold;">' . $leave['toAdmin'] .',</span></h1>' .'</div>' .'</div>' . '<div class="card-body text-dark">' .'<p style="color:black">' . 'My name is <span style="font-weight: bold;"> ' . Auth::user()->name . '</span> , has employee id:<strong> ' . $leave['employee_id'] . '</strong> , is a <span style="font-weight: bold;"> '. $employee->position .'</span> of department <span style="font-weight: bold;">' . $employee->department . '</span>.' .  '</p>' . '<p style="color:black">';
        $random1 = rand(0, 100)/100;
        if ($random1 < 1/4)
            $my_format .= 'I  would like to ask permission for ';
        else if ($random1 < 2/4)
            $my_format .= 'I am writing to request your approval for ';
        else if ($random1 < 3/4)
            $my_format .= 'This letter concerns my request for ';
        else
            $my_format .= 'I am writing this letter to let you know about my requirement for ';

        if ($leave['number_day'] <= 7) {
            $my_format .= $digit_to_char[$leave['number_day']];
        } else {
            $my_format = $my_format . $leave['number_day'] . 'days';
        }

        $my_format .= ' off from work ';
        if ($leave['multidays'] === 'Yes') {
            $my_format .= 'from <span style="font-weight: bold;">' . $leave['start_leave_day'] . ' </span> to <span style="font-weight: bold;"> ' . $leave['end_leave_day'] . '</span> because ' . $leave['reason'];
        } else {
            $my_format .= 'on <span style="font-weight: bold;">' . $leave['leave_day'] . '</span> because ' . $leave['reason'] .'.';
        }
        $my_format .= '</p>' .'<p style="color:black">' . 'In description, ' . $leave['description'] . '.</p>' .'</div>' . '<div class="card-footer">';
        $random2 = rand(0, 100)/100;
        if ($random2 < 1/3) {
            $my_format .= '<p style="color:black">' .'Thank you very much for your consideration.' . '</p>' . '<p style="color:black">' .'With kind regards,' . '</p>';
        } else if ($random2 < 2/3) {
            $my_format .= '<p style="color:black">' . 'Hoping for your kind consideration.' . '</p>' . '<p style="color:black">' . 'Sincerely Yours,' . '</p>';
        } else {
            $my_format .= '<p style="color:black">' . 'Thank you and regards,' . '</p>';
        }
        $my_format .= '<p style="color:black">' . '<span style="font-weight: bold;">' . Auth::user()->name . '</span' .  '</p>' . '</div>';
        Format_Leave::create([
            'leave_id' => $created_leave->id,
            'format' => $my_format,
        ]);

        return back()->with(['message' => 'Create form successfully!']);
    }

    public function list_leaves(){
        /* Check get all or month by: title and request*/

        $all = false;
        $month = false;
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
        /* Get leave of this employee */
        $employee = Auth::user()->employee()->first();
        if ($all) {
            $title = 'all';
            $list_leaves = Leave::with('employee')->where('employee_id', $employee->id)->get();
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
            $list_leaves = Leave::with('employee')->where('employee_id', $employee->id)->where('month', Carbon::parse($month)->format("Y-m"))->get();
        } else {
            $title = ' this month';
            $month = $this->curMonth();
            $list_leaves = Leave::with('employee')->where('employee_id', $employee->id)->where('month',$month)->get();
        }

        $notSeen_leaves = array();
        $declined_leaves = array();
        $accepted_leaves = array();

        foreach ($list_leaves as $leave){
            if ($leave->status == 'Not seen') {
                array_push($notSeen_leaves, $leave);
            } elseif ($leave->status == 'Declined') {
                array_push($declined_leaves, $leave);
            } elseif ($leave->status == 'Accepted'){
                array_push($accepted_leaves, $leave);
            }
        }

        $data = [
            'title' => $title,
            'not_seen' => $notSeen_leaves,
            'declined' => $declined_leaves,
            'accepted' => $accepted_leaves,
        ];

        return view('employee.leave.list_leaves')->with($data);
    }
}
