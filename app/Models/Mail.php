<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;

    protected $table = 'mails';

    protected $fillable = [
        'sender',
        'receivers',
        'subject',
        'content',
        'read',
        'attachment',
        'response_to',
        'response_type',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'mail_user')->withPivot('id', 'deleted', 'favorite', 'important', 'label')->withTimestamps();
    }
}
