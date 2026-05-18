<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'name',
        'created_by'
    ];
    public function participants()
    {
        return $this->belongsToMany(
            User::class,
            'conversation_participants'
        )
        ->withPivot([
            'joined_at',
            'last_read_at'
        ])
        ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
