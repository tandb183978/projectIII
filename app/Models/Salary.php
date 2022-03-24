<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $table='salaries';

    protected $fillable = [
        'name',
        'month',
        'employee_id',
        'subsidy',
        'allowance',
        'insurance',
        'take_home_pay',
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function info_detail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Info_Details_Month::class);
    }
}
