<?php

namespace App\Models;

use Database\Factories\ApiLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    /** @use HasFactory<ApiLogFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'method',
        'ip',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
