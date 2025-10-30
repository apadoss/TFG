<?php

namespace App\Console\Commands;

use App\Jobs\SendPriceDropNotification;
use App\Models\PriceNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessPriceNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-price-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa y envía notificaciones de bajadas de precio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Procesando notificaciones de bajadas de precio...');

        // Obtener notificaciones pendientes
        $pendingNotifications = DB::table('pending_notifications')
            ->where('processed', false)
            ->get();

        if ($pendingNotifications->isEmpty()) {
            $this->info('No hay notificaciones pendientes.');
            return 0;
        }

        $count = 0;

        foreach ($pendingNotifications as $pending) {
            // Buscar usuarios que quieren ser notificados
            $notifications = PriceNotification::where('component_type', $pending->component_type)
                ->where('component_id', $pending->component_id)
                ->where('is_active', true)
                ->with('user')
                ->get();

            foreach ($notifications as $notification) {
                if ($notification->shouldNotify($pending->new_price, $pending->old_price)) {
                    $component = app($pending->component_type)->find($pending->component_id);
                    
                    if ($component) {
                        // Encolar el envío del email
                        SendPriceDropNotification::dispatch(
                            $notification,
                            $component,
                            $pending->old_price,
                            $pending->new_price,
                            $pending->vendor
                        );
                        
                        $count++;
                        $this->info("Notificación encolada para {$notification->user->email}");
                    }
                }
            }

            // Marcar como procesada
            DB::table('pending_notifications')
                ->where('id', $pending->id)
                ->update(['processed' => true]);
        }

        $this->info("Se encolaron {$count} notificaciones.");
        
        // Limpiar notificaciones procesadas antiguas (más de 7 días)
        $deleted = DB::table('pending_notifications')
            ->where('processed', true)
            ->where('created_at', '<', now()->subDays(7))
            ->delete();

        if ($deleted > 0) {
            $this->info("Se eliminaron {$deleted} notificaciones antiguas.");
        }

        return 0;
    }
}
