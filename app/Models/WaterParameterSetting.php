<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterParameterSetting extends Model
{
    // Mengizinkan kolom ini diisi
    protected $fillable = [
        'device_id', 'min_ph', 'max_ph', 'min_temp', 'max_temp', 'max_turbidity'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
