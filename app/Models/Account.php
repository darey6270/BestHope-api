<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Account extends Model
{
    use HasFactory;


protected $fillable = [
    'bank_name',
    'account_holder_name',
    'account_number',
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
