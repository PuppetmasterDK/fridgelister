<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['fridge_id', 'added_by', 'name', 'quantity', 'unit', 'best_before', 'used_at'];

    protected function casts(): array
    {
        return [
            'best_before' => 'date',
            'used_at' => 'datetime',
            'quantity' => 'float',
        ];
    }

    public function fridge(): BelongsTo
    {
        return $this->belongsTo(Fridge::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
