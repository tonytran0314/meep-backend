<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['seen', 'sender_id', 'receiver_id', 'type'];

    public function sender() {
        return $this->belongsTo(User::class);
    }
}
