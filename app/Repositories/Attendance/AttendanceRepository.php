<?php

namespace App\Repositories\Attendance;
use App\Models\Attendance;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class AttendanceRepository extends BaseRepository implements \App\Repositories\Attendance\AttendanceRepositoryInterface {
    /**
     * Lấy model
     */
    public function getModel()
    {
        return Attendance::class;
    }

    public function custom_create($employee_id, $date, $entry_time = null, $exit_time = null, $status = 'Absent', $working_hours = "00:00:00")
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        return $this->model->create([
            'employee_id' => $employee_id,
            'date' => $date,
            'entry_time' => $entry_time,
            'exit_time' => $exit_time,
            'status' => $status,
            'working_hours' => $working_hours,
        ]);
    }


}
