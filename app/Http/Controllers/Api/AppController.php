<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaterLog;
use App\Models\Notification;
use App\Models\WaterParameterSetting;

class AppController extends Controller
{
    // A. FUNGSI LONCENG NOTIFIKASI: Menampilkan riwayat bahaya ke aplikasi
    public function getNotifications($device_id)
    {
        $notifs = Notification::where('device_id', $device_id)
                              ->orderBy('created_at', 'desc')
                              ->take(20) // Ambil 20 data terbaru saja
                              ->get();

        return response()->json(['success' => true, 'data' => $notifs]);
    }

    // B. FUNGSI UPDATE SETTINGS: User ganti batas aman lewat aplikasi
    public function updateSettings(Request $request, $device_id)
    {
        $settings = WaterParameterSetting::where('device_id', $device_id)->first();

        if (!$settings) {
            return response()->json(['message' => 'Setting untuk device ini belum ada!'], 404);
        }

        // Validasi input agar tidak asal isi
        $validated = $request->validate([
            'min_ph' => 'numeric',
            'max_ph' => 'numeric',
            'min_temp' => 'numeric',
            'max_temp' => 'numeric',
            'max_turbidity' => 'numeric',
        ]);

        $settings->update($validated);

        return response()->json(['success' => true, 'message' => 'Batas aman diperbarui!']);
    }

    // C. FUNGSI REAL-TIME: Ambil data sensor terakhir untuk dashboard aplikasi
    public function getLatestData($device_id)
    {
        $data = WaterLog::where('device_id', $device_id)
                        ->orderBy('created_at', 'desc')
                        ->first();

        return response()->json(['success' => true, 'data' => $data]);
    }
}
