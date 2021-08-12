<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TodoNote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'content',
        'completion_time'
    ];


    /**
     * Get user that note belongs to
     *
     * @return User
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
