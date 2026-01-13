<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaterLog;
use App\Models\Device;
use App\Models\Notification;
use App\Models\WaterParameterSetting; // Import model baru
use Illuminate\Support\Facades\Log;

class WaterLogController extends Controller
{
    public function store(Request $request)
    {
        if ($request->header('X-API-KEY') !== 'RahasianyaSastra123') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $request->validate([
                'mac_address' => 'required|exists:devices,mac_address',
                'temperature' => 'required|numeric',
                'ph'          => 'required|numeric',
                'turbidity'   => 'required|numeric',
            ]);

            $device = Device::where('mac_address', $request->mac_address)->first();

            // MENGAMBIL ATURAN DARI DATABASE
            $settings = WaterParameterSetting::where('device_id', $device->id)->first();

            // Gunakan nilai dari database, jika kosong gunakan angka standar
            $minPh = $settings->min_ph ?? 6.5;
            $maxPh = $settings->max_ph ?? 8.5;
            $maxTemp = $settings->max_temp ?? 35;
            $maxTurb = $settings->max_turbidity ?? 500;

            // Logika Penentuan Status (Sekarang Dinamis!)
            $status = 'NORMAL';
            if ($request->ph < $minPh || $request->ph > $maxPh) {
                $status = 'DANGER_PH';
            } elseif ($request->temperature > $maxTemp) {
                $status = 'DANGER_TEMP';
            } elseif ($request->turbidity > $maxTurb) {
                $status = 'DANGER_TURBIDITY';
            }

            $log = WaterLog::create([
                'device_id'   => $device->id,
                'temperature' => $request->temperature,
                'ph'          => $request->ph,
                'turbidity'   => $request->turbidity,
                'status_code' => $status, // Pastikan ini integer sesuai migrasi Anda
            ]);

            if ($status !== 'NORMAL') {
                Notification::create([
                    'device_id' => $device->id,
                    'type'      => 'ALERT',
                    'message'   => "Peringatan! Kondisi {$status} terdeteksi. (Batas pH: {$minPh}-{$maxPh})",
                    'is_read'   => false
                ]);
            }

            return response()->json(['success' => true, 'data' => $log], 201);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
