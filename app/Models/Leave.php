<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table='leaves';
    protected $fillable=[
        'employee_id',
        'reason',
        'description',
        'multidays',
        'status',
        'month',
        'toAdmin',
        'number_day',
        'leave_day',
        'start_leave_day',
        'end_leave_day',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function format_leave(){
        return $this->hasOne(Format_Leave::class);
    }
}
