<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // 1. Mengambil semua notifikasi (Untuk ditampilkan di aplikasi/web)
    public function index()
    {
        $notifications = Notification::with('device')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $notifications
        ]);
    }

    // 2. Menandai notifikasi sebagai "Sudah Dibaca"
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notifikasi tidak ditemukan'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca.'
        ]);
    }

    // 3. Menghapus notifikasi lama (Maintenance database)
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Berhasil dihapus']);
        }

        return response()->json(['message' => 'Gagal menghapus, data tidak ada'], 404);
    }
}
