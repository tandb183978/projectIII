<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birth_day',
        'gender',
        'phone',
        'department',
        'position',
        'address',
        'country',
        'image_profile',
        'base_salary',
        'max_leaves',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function salaries(){
        return $this->hasMany(Salary::class);
    }

    public function leaves(){
        return $this->hasMany(Leave::class);
    }

}
