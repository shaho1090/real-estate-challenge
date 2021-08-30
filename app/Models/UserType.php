<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @return Builder|Model|object|null
     * @throws Exception
     */
    public static function landlord()
    {
        return self::getUserType('landlord');
    }

    /**
     * @throws Exception
     */
    public static function customer()
    {
        return self::getUserType('customer');
    }

    /**
     * @throws Exception
     */
    public static function employee()
    {
        return self::getUserType('employee');
    }

    /**
     * @param string $type
     * @return Builder|Model|object|null
     * @throws Exception
     */
    private static function getUserType(string $type)
    {
        if(!$userType = self::query()->where('title',$type)->first()){
            throw new Exception('The '.$type.' user type was not found!');
        }

        return $userType;
    }
}
