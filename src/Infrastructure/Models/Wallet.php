<?php

namespace Src\Infrastructure\Models;

use Database\Factories\WalletFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Wallet extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'balance',
        'owner_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    protected static function newFactory(): WalletFactory
    {
        return WalletFactory::new();
    }
}
