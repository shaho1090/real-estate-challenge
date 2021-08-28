<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time'
    ];

    public function employee():BelongsTo
    {
        return $this->belongsTo(User::class,'employee_id');
    }

    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class,'home_id');
    }

    /**
     * @param array $request
     * @return Appointment
     * @throws Exception
     */
    public function createNewAppointment(array $request): Appointment
    {
        if(!User::query()->find($request['employee_id'])->type_id === UserType::employee()->id){
            throw new Exception('The type the user is not employee for appointment!');
        }

        $this->attributes['employee_id'] = $request['employee_id'];
        $this->attributes['home_id'] = $request['home_id'];
        $this->attributes['date'] = Carbon::parse($request['date'])->toDateTimeString();

        $this->save();

        return $this;
    }
}
