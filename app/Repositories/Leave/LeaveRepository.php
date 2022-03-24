<?php

namespace App\Repositories\Leave;

use App\Models\Leave;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class LeaveRepository extends BaseRepository implements LeaveRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return Leave::class;
    }

    public function arrayOfExistDay($employee_id, $month, $statuses = array('Accepted', 'Declined', 'Not seen')): array
    {
        $leaves = $this->model->where('employee_id', $employee_id)->where('month', $month)->get();
        $isExist = array_fill(0, 32, false);
        foreach ($leaves as $leave){
            if (in_array($leave->status, $statuses)) {
                if ($leave->leave_day) {     // Single day
                    $isExist[(int)(Carbon::parse($leave->leave_day)->format("d"))] = true;
                } else {                    // Multiple day
                    $start = (int)(Carbon::parse($leave->start_leave_day)->format("d"));
                    $end = (int)(Carbon::parse($leave->end_leave_day))->format("d");
                    for ($i = $start; $i <= $end; $i++) {
                        $isExist[$i] = true;
                    }
                }
            }
        }
        return $isExist;
    }
}
