<?php

namespace App\Jobs;

use App\Mail\PriceDropNotification;
use App\Models\PriceNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPriceDropNotification implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $notification;
    protected $component;
    protected $oldPrice;
    protected $newPrice;
    protected $vendor;

    /**
     * Create a new job instance.
     */
    public function __construct(PriceNotification $notification, $component, $oldPrice, $newPrice, $vendor)
    {
        $this->notification = $notification;
        $this->component = $component;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->vendor = $vendor;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $componentType = $this->getComponentType($this->component);
            $componentUrl = route('componentes.view', [
                'type' => $componentType,
                'id' => $this->component->id  
            ]);

            Mail::to($this->notification->user->email)
                ->send(new PriceDropNotification(
                    $this->component,
                    $this->oldPrice,
                    $this->newPrice,
                    $componentUrl,
                    $this->vendor
                ));
            
            $this->notification->update([
                'last_notified_at' => now()
            ]);

            Log::info('Notificación de bajada de precio enviada', [
                'user_id' => $this->notification->user_id,
                'component' => $this->component->name,
                'old_price' => $this->oldPrice,
                'new_price' => $this->newPrice
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar notificación de precio', [
                'error' => $e->getMessage(),
                'user_id' => $this->notification->user_id,
                'component_id' => $this->component->id
            ]);

            throw $e;
        }
    }

    private function getComponentType($component): string {
        $class = get_class($component);
        $map = [
            'App\Models\componentes\Procesador' => 'procesadores',
            'App\Models\componentes\TarjetaGrafica' => 'tarjetas-graficas',
            'App\Models\componentes\PlacasBase' => 'placas-base',
            'App\Models\componentes\MemoriaRam' => 'ram',
            'App\Models\componentes\Almacenamiento' => 'almacenamiento',
            'App\Models\componentes\FuenteAlimentacion' => 'fuentes-alimentacion',
            'App\Models\componentes\Portatil' => 'portatiles'
        ];

        foreach ($map as $modelName => $route) {
            if (str_contains($class, $modelName)) {
                return $route;
            }
        }

        return 'componentes';
    }
}
