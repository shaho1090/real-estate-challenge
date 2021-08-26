<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use mysql_xdevapi\Exception;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'family',
        'email',
        'password',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(UserType::class,'type_id');
    }

    /**
     * @param array $request
     * @return User
     */
    public function createACustomer(array $request): User
    {
        $customerType = UserType::customer();

        if(is_null($customerType)){
            throw new Exception('The customer type was not found!');
        }

        $this->attributes['name'] = $request['name'];
        $this->attributes['family'] = $request['family'];
        $this->attributes['phone'] = $request['phone'];
        $this->attributes['email'] = $request['email'];
        $this->attributes['address'] = $request['address'];
        $this->attributes['password'] = bcrypt($request['password']);
        $this->attributes['type_id'] = $customerType->id;
        $this->save();

        return $this;
    }

    public function homes(): HasMany
    {
        return $this->hasMany(Home::class,'customer_id');
    }

    /**
     * @param array $request
     * @return Model
     */
    public function createHome(array $request): Model
    {
          $home = $this->homes()->make();

          $home->attributes['zip_code'] = $request['zip_code'];
          $home->attributes['purpose'] = $request['purpose'];
          $home->attributes['title'] = $request['title'];
          $home->attributes['type_id'] = $request['type_id'];
          $home->attributes['price'] = $request['price'];
          $home->attributes['bedrooms'] = $request['bedrooms'];
          $home->attributes['bathrooms'] = $request['bathrooms'];
          $home->attributes['condition_id'] = $request['condition_id'];
          $home->attributes['m_two'] = $request['m_two'];
          $home->attributes['price_m_two'] = $request['price_m_two'];
          $home->attributes['address'] = $request['address'];

          $home->save();

          return $home;
    }
}
