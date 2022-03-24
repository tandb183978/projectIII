<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Repositories\Salary\SalaryRepository;
use App\Repositories\Salary\SalaryRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $salaryRepo;

    public function __construct(SalaryRepository $salaryRepo){
        $this->salaryRepo = $salaryRepo;
    }

    public function curMonth(): string
    {
        return Carbon::now('Asia/Ho_Chi_Minh')->format("Y-m");
    }

    public function curDate(): string
    {
        return Carbon::now('Asia/Ho_Chi_Minh')->format("Y-m-d");
    }

    public function getDate($date): string
    {
        return Carbon::parse($date)->format("Y-m-d");
    }

    public function curDay(): int
    {
        return (int) Carbon::now('Asia/Ho_Chi_Minh')->format("d");
    }


    public function earlyMonthOrNot(): bool
    {
        $myFile = fopen(storage_path("app/public/month.txt"), 'r+') or die('Unable to read this file');
        $content = fread($myFile,filesize(storage_path("app/public/month.txt")));
        $data = explode(' ', $content);
        $pre_month = trim($data[0]);
        $cur_month = trim($data[1]);
        $this_month = $this->curMonth();
        if ($this_month != $cur_month) {
            $new_content = $cur_month.' '.$this_month;
            file_put_contents(storage_path("app/public/month.txt"),$new_content);
            return true;
        }
        return false;
    }

    public function time_to_decimal($time){
        $timeArr = explode(':', $time);
        return ($timeArr[0]) + ($timeArr[1])/60 + ($timeArr[2])/3600;
    }

    public function update_info_details_month($employee_id, $overtime_hours, $undertime_hours, $dayoff = 0, $dayon = 0, $dayleft = 0){
        $salary = $this->salaryRepo->findSalaryCurrentMonthByEmployeeId($employee_id);
        $info_details_month = $salary->info_detail()->first();
        /* Chuyển số giờ làm việc của employee về dạng float */

        $info_details_month->number_dayoff += $dayoff;
        $info_details_month->number_dayon += $dayon;
        $info_details_month->number_dayleft += $dayleft;
        $info_details_month->overtime_workings += $overtime_hours;
        $info_details_month->undertime_workings += $undertime_hours;

        $info_details_month->save();
    }

}
