<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterLog extends Model
{
    protected $fillable = ['device_id', 'temperature', 'ph', 'turbidity', 'status_code'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
