<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info_Details_Month extends Model
{
    use HasFactory;
    protected $table = 'info_details_months';

    protected $fillable=[
        'salary_id',
        'month',
        'number_day',
        'number_dayon',
        'number_dayoff',
        'number_dayleft',
        'overtime_workings',
        'undertime_workings',
    ];

    public function salary(){
        return $this->belongsTo(Salary::class, 'salary_id');
    }
}
