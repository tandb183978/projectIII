<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Format_Leave extends Model
{
    use HasFactory;

    protected $table = 'format_leaves';
    protected $fillable = [
        'leave_id',
        'format',
    ];

    public function leave(){
        $this->belongsTo(Leave::class, 'leave_id');
    }
}
