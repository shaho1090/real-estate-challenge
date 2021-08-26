<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Home extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'purpose',
        'zip_code',
        'address',
        'price',
        'bedrooms',
        'bathrooms',
        'm_two',
        'price_m_two'
    ];

    public function condition():belongsTO
    {
        return $this->belongsTo(HomeCondition::class,'condition_id');
    }

    public function type():belongsTo
    {
        return $this->belongsTo(HomeType::class,'type_id');
    }

    public function storeNew()
    {

    }
}
