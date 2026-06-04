<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Share extends Model
{
    use HasFactory;

    protected $fillable = ['fridge_id', 'email', 'user_id', 'status', 'shop_by', 'responded_at'];

    protected function casts(): array
    {
        return [
            'shop_by' => 'date',
            'responded_at' => 'datetime',
        ];
    }

    public function fridge(): BelongsTo
    {
        return $this->belongsTo(Fridge::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
