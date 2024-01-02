<?php

namespace Src\Infrastructure\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Src\Domain\Enum\DocumentTypeEnum;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
        'document_number',
        'document_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class,'owner_id');
    }

    public function isMerchant(): bool
    {
        if ($this->type === DocumentTypeEnum::CNPJ->value) {
            return true;
        }

        return false;
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
