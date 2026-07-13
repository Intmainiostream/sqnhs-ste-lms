<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountChangeRequest extends Model
{
    protected $fillable = [
        'user_id', 'new_username', 'new_email', 'new_password', 'changes',
        'status', 'admin_remarks', 'reviewed_at',
    ];

    protected $casts = [
        'changes'     => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}