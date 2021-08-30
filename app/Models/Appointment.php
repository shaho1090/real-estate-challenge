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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class,'customer_id');
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
        $this->attributes['customer_id'] = $request['customer_id'];
        $this->attributes['date'] = Carbon::parse($request['date'])->toDateTimeString();

        $this->save();

        return $this;
    }

    /**
     * @throws Exception
     */
    public function start(): Appointment
    {
        if($this->isStarted()){
            throw new Exception('This appointment already is started!');
        }

        $this->attributes['start_time'] = Carbon::now()->toDateTimeString();
        $this->save();

        return $this;
    }

    /**
     * @throws Exception
     */
    public function end(): Appointment
    {
        if(!$this->isStarted()){
            throw new Exception('This appointment is not started yet!');
        }

        if($this->isEnded()){
            throw new Exception('This appointment already is ended!');
        }

        $this->attributes['end_time'] = Carbon::now()->toDateTimeString();
        $this->save();

        return $this;
    }

    public function isStarted(): bool
    {
       return $this->attributes['start_time'] !== null;
    }

    public function isEnded(): bool
    {
        return $this->attributes['end_time'] !== null;
    }
}
