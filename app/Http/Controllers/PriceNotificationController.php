<?php

namespace App\Http\Controllers;

use App\Models\PriceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceNotificationController extends Controller
{
    public function toggle(Request $request) {
        $request->validate([
            'component_type' => 'required|string',
            'component_id' => 'required|integer',
            'target_price' => 'nullable|numeric|min:0',
            'notify_any_drop' => 'boolean'
        ]);

        $notifyAnyDrop = $request->notify_any_drop;
        $targetPrice = $request->target_price;
        
        if ($targetPrice) {
            $notifyAnyDrop = false;
        }

        $notification = PriceNotification::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'component_type' => $request->component_type,
                'component_id' => $request->component_id,
            ],
            [
                'target_price' => $targetPrice,
                'notify_any_drop' => $notifyAnyDrop,
                'is_active' => true,
            ]
        );

        $message = $targetPrice 
            ? "¡Notificación activada! Te avisaremos cuando llegue a {$targetPrice}€ o menos."
            : '¡Notificación activada! Te avisaremos cuando baje el precio.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'notification' => $notification
    ]);
    }

    public function deactivate(Request $request) {
        $request->validate([
            'component_type' => 'required|string',
            'component_id' => 'required|integer',
        ]);

        $notification = PriceNotification::where('user_id', Auth::id())
            ->where('component_type', $request->component_type)
            ->where('component_id', $request->component_id)
            ->first();
        
        if ($notification) {
            $notification->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Notificación desactivada.'
                ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No se encontró la notificación.'
        ], 404);
    }

    public function index() {
        $notifications = Auth::user()
            ->priceNotifications()
            ->with('component')
            ->where('is_active', true)
            ->latest()
            ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function check(Request $request) {
        $request->validate([
            'component_type' => 'required|string',
            'component_id' => 'required|integer',
        ]);

        $notification = PriceNotification::where('user_id', Auth::id())
            ->where('component_type', $request->component_type)
            ->where('component_id', $request->component_id)
            ->where('is_active', true)
            ->first();
    
        return response()->json([
            'has_notification' => (bool) $notification,
            'notification' => $notification
        ]);
    } 
}
