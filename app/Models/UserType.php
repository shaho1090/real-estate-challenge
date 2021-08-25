<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserType extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class,'type_id');
    }

    public static function customer()
    {
        return self::query()->where('title','customer')->first();
    }

    public static function employee()
    {
        return self::query()->where('title','employee')->first();
    }
}
