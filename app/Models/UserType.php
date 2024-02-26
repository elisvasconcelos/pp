<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 *
 * @method where(string $string, $typeId)
 */
class UserType extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    public const CLIENT_TYPE = 'Cliente';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Verify if type can be a payer.
     */
    public function isPayer(): bool
    {
        return $this->name === self::CLIENT_TYPE;
    }
}
