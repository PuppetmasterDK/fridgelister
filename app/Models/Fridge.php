<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fridge extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    public function isSharedWith(User $user): bool
    {
        return $this->shares()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }
}
