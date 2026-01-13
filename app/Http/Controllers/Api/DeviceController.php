<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'device_name' => 'required|string|max:255',
            'mac_address' => 'required|string|unique:devices,mac_address',
            'location'    => 'nullable|string',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors'  => $validator->errors()
            ], 422);
        }


        $device = Device::create([
            'device_name' => $request->device_name,
            'mac_address' => $request->mac_address,
            'location'    => $request->location,
            'is_active'   => true,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Perangkat Berhasil Didaftarkan!',
            'data'    => $device
        ], 201);
    }

    public function destroy($id) {
    $device = Device::find($id);
    if (!$device) {
        return response()->json(['message' => 'Device tidak ditemukan'], 404);
    }

    $device->delete(); // Ini akan menghapus data di tabel devices
    return response()->json(['message' => 'Device berhasil dihapus'], 200);
}
}
