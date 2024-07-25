<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    public function commentor()
    {
        return $this->belongsTo(Admin::class, 'commented_by');
    }

    public function getFormattedDateAttribute()
    {
        return showDateTime($this->created_at);
    }
}