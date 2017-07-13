<?php

namespace LaravelEnso\Risco\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\TrackWho\app\Traits\UpdatedBy;

class SubscribedApp extends Model
{
    use CreatedBy, UpdatedBy;

    protected $fillable = ['url', 'client_id', 'secret', 'token', 'name', 'description', 'type', 'preferences'];
    protected $hidden = ['secret', 'token'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getPreferencesAttribute($value)
    {
        return (object) json_decode($value, true);
    }
}
