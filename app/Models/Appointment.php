<?php

namespace App\Models;

use App\AppointmentConfig;
use App\Distance;
use App\RealEstateOffice;
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
        'end_time',
        'distance_estimated_time'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function home(): BelongsTo
    {
        return $this->belongsTo(Home::class, 'home_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * @param array $request
     * @return Appointment
     * @throws Exception
     */
    public function createNewAppointment(array $request): Appointment
    {
        if (!User::query()->find($request['employee_id'])->type_id === UserType::employee()->id) {
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
     * @return $this
     * @throws Exception
     */
    public function startFromOffice(): Appointment
    {
        $distanceEstimatedTime =
            (new Distance(RealEstateOffice::zipCode(), $this->home()->first()->zip_code))
                ->estimate()->inMinute();

        $this->attributes['start_time'] = Carbon::now()->toDateTimeString();
        $this->attributes['distance_estimated_time'] = $distanceEstimatedTime;
        $this->save();

        return $this;
    }

    public function getLatestAppointmentZipCode()
    {
        return (new MyAppointment())->getLatest()->home()->first()->zip_code;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function startFromPreviousAppointment(): Appointment
    {
        $distanceEstimatedTime =
            (new Distance($this->getLatestAppointmentZipCode(), $this->home()->first()->zip_code))
                ->estimate()->inMinute();

        $this->attributes['start_time'] = Carbon::now()->toDateTimeString();
        $this->attributes['distance_estimated_time'] = $distanceEstimatedTime;
        $this->save();

        return $this;
    }

    /**
     * @throws Exception
     */
    public function start(string $origin): Appointment
    {
        if ($this->isStarted()) {
            throw new Exception('This appointment already is started!');
        }

        if ($origin === 'office') {
            return $this->startFromOffice();
        }

        return $this->startFromPreviousAppointment();
    }

    /**
     * @throws Exception
     */
    public function end(): Appointment
    {
        if (!$this->isStarted()) {
            throw new Exception('This appointment is not started yet!');
        }

        if ($this->isEnded()) {
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

    public function probableEmployeeFreeTime(): ?string
    {
        if($this->isEnded()){
            return 'ended';
        }

        if((!is_null($this->start_time)) && (!is_null($this->distance_estimated_time))){
               return Carbon::parse($this->start_time)
                   ->addMinutes($this->distance_estimated_time + AppointmentConfig::duration())
                   ->toDateTimeString();
        }

        return null;
    }
}
