<?php

namespace App\Http\Controllers;

use App\Models\componentes\Procesador;
use App\Models\componentes\TarjetaGrafica;
use App\Models\componentes\PlacasBase;
use App\Models\componentes\Almacenamiento;
use App\Models\componentes\MemoriaRam;
use App\Models\componentes\FuenteAlimentacion;
use App\Models\componentes\Portatil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComponentesController extends Controller
{
    public function index(Request $request) {
        $segment = $request->segment(2);
        $name = $request->name;
        $products = [];

        switch ($segment) {
            case 'procesadores':
                $query = Procesador::query();

                if ($request->socket) {
                    $query->where('socket', $request->socket);
                }
                if ($request->cores_min) {
                    $query->where('n_cores', '>=', $request->cores_min);
                }
                if ($request->cores_max) {
                    $query->where('n_cores', '<=', $request->cores_max);
                }
                if ($request->clock_min) {
                    $query->where('clock_speed', '>=', $request->clock_min);
                }
                if ($request->clock_max) {
                    $query->where('clock_speed', '<=', $request->clock_max);
                }
                if ($request->igpu) {
                    $query->where('integrated_graphics', true);
                }

                break;
            case 'tarjetas-graficas':
                $query = TarjetaGrafica::query();

                if ($request->vram_min) {
                    $query->where('vram', '>=', $request->vram_min);
                }
                if ($request->vram_max) {
                    $query->where('vram', '<=', $request->vram_max);
                }
                if ($request->mem_type) {
                    $query->where('mem_type', $request->mem_type);
                }
                if ($request->interface) {
                    $query->where('interface', $request->interface);
                }

                break;
            case 'placas-base':
                $query = PlacasBase::query();

                if ($request->socket) {
                    $query->where('socket', $request->socket);
                }
                if ($request->form_factor) {
                    $query->where('form_factor', $request->form_factor);
                }
                if ($request->chipset) {
                    $query->where('chipset', $request->chipset);
                } 

                break;
            case 'almacenamiento':
                $query = Almacenamiento::query();

                if ($request->type) {
                    $query->where('type', $request->type);
                }
                if ($request->capacity_min) {
                    $query->where('capacity', '>=', $request->capacity_min);
                }
                if ($request->capacity_max) {
                    $query->where('capacity', '<=', $request->capacity_max);
                }

                break;
            case 'ram':
                $query = MemoriaRam::query();

                if ($request->type) {
                    $query->where('type', $request->type);
                }
                if ($request->speed_min) {
                    $query->where('speed', '>=', $request->speed_min);
                }
                if ($request->speed_max) {
                    $query->where('speed', '<=', $request->speed_max);
                }
                if ($request->latency_min) {
                    $query->where('latency', '>=', $request->latency_min);
                }
                if ($request->latency_max) {
                    $query->where('latency', '<=', $request->latency_max);
                }
                if ($request->capacity_min) {
                    $query->where('capacity', '>=', $request->capacity_min);
                }
                if ($request->capacity_max) {
                    $query->where('capacity', '<=', $request->capacity_max);
                }

                break;
            case 'fuentes-alimentacion':
                $query = FuenteAlimentacion::query();

                if ($request->power_min) {
                    $query->where('power', '>=', $request->power_min);
                }
                if ($request->power_max) {
                    $query->where('power', '<=', $request->power_max);
                }
                if ($request->certification) {
                    $query->where('certification', $request->certification);
                }

                break;
            case 'portatiles':
                $query = Portatil::query();
                break;
            default:
                abort(404);
        }

        if ($request->brand) {
                $query->where('brand', $request->brand);
        }
        
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        $table = $query->getModel()->getTable();

        $uniqueIds = DB::table($table)
                   ->select(DB::raw('MIN(id) as id'))
                   ->groupBy('name')
                   ->pluck('id')
                   ->toArray();

        $query->whereIn('id', $uniqueIds);

        $query = $this->sortComponents($query, $request);
        
        $products = $query->paginate(15)->appends($request->query());

        return view('componentes.index', compact('products'));
    }

    public function view(Request $request) {
        $type = $request->segment(2);
        $product = null;

        switch ($type) {
            case 'procesadores':
                $product = Procesador::find($request->id);
                $model = new Procesador();
                break;
            case 'tarjetas-graficas':
                $product = TarjetaGrafica::find($request->id);
                $model = new TarjetaGrafica();
                break;
            case 'placas-base':
                $product = PlacasBase::find($request->id);
                $model = new PlacasBase();
                break;
            case 'almacenamiento':
                $product = Almacenamiento::find($request->id);
                $model = new Almacenamiento();
                break;
            case 'ram':
                $product = MemoriaRam::find($request->id);
                $model = new MemoriaRam();
                break;
            case 'fuentes-alimentacion':
                $product = FuenteAlimentacion::find($request->id);
                $model = new FuenteAlimentacion();
                break;
            case 'portatiles':
                $product = Portatil::find($request->id);
                $model = new Portatil();
                break;
            default:
                abort(404);
            }
        
        $table = $model->getTable();
        $variants = DB::table($table)
                        ->where('name', $product->name)
                        ->get();
        
        $pricesByVendor = [];
        $urlsByVendor = [];

        foreach ($variants as $variant) {
            if (isset($variant->vendor) && isset($variant->price)) {
                $pricesByVendor[$variant->vendor] = $variant->price;
                
                // Guardamos también las URLs si están disponibles
                if (isset($variant->url)) {
                    $urlsByVendor[$variant->vendor] = $variant->url;
                }
            }
        }

        $product->prices_by_vendor = $pricesByVendor;
        $product->urls_by_vendor = $urlsByVendor;

        return view('componentes.view', compact('product', 'type'));
    }

    public function compare($type, $product1Id, $product2Id = null) {
        $product1 = null;
        $product2 = null;
        $allProducts = [];

        switch ($type) {
            case 'procesadores':
                $model = Procesador::class;
                break;
            case 'tarjetas-graficas':
                $model = TarjetaGrafica::class;
                break;
            case 'placas-base':
                $model = PlacasBase::class;
                break;
            case 'almacenamiento':
                $model = Almacenamiento::class;
                break;
            case 'ram':
                $model = MemoriaRam::class;
                break;
            case 'fuentes-alimentacion':
                $model = FuenteAlimentacion::class;
                break;
            case 'portatiles':
                $model = Portatil::class;
                break;
            default:
                abort(404);
        }

        $product1 = $model::findOrFail($product1Id);
        $allProducts = $model::where('id', '!=', $product1Id)->get();

        if ($product2Id) {
            $product2 = $model::findOrFail($product2Id);
        }

        return view('componentes.compare', compact('product1', 'product2', 'allProducts', 'type'));
    }

    /**
     * Método privado para obtener componentes de la base de datos
     */
    private function getComponents($modelOrQuery, Request $request)
    {
        if (is_string($modelOrQuery)) {
            $query = $modelOrQuery::query();
        } else {
            $query = $modelOrQuery;
        }

        $table = $query->getModel()->getTable();
        
        // Construir la subconsulta con precios minimos
        $subQuery = "SELECT name, MIN(price) as min_price FROM {$table}";
        if ($request->has('name')) {
            $subQuery .= " WHERE name LIKE '%" . addslashes($request->name) . "%'";
        }
        $subQuery .= " GROUP BY name";
        
        $query->join(DB::raw("({$subQuery}) as min_prices"), function($join) use ($table) {
            $join->on("{$table}.name", '=', 'min_prices.name')
                 ->on("{$table}.price", '=', 'min_prices.min_price');
        });
        
        // Aplicar filtro de nombre si existe
        if ($request->has('name')) {
            $query->where("{$table}.name", 'like', '%' . $request->name . '%');
        }

        return $query->get();
    }

    /**
     * Método privado para ordenar componentes
     */

    private function sortComponents($query, $request) {
        $sortBy = $request->get('sort_by', '');
        $sortOrder = $request->get('sort_order', '');

        $allowedSorts = ['name', 'price'];
        $allowedOrders = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSorts) && in_array($sortOrder, $allowedOrders)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    } 

    public function getCpus(Request $request) 
    {
        $query = Procesador::query();

        if ($request->has('motherboard_id')) {
            $motherboard = PlacasBase::find($request->motherboard_id);
            if ($motherboard) {
                $query->where('socket', $motherboard->socket);
            }
        }
    
        $cpus = $this->getComponents($query, $request);
        return response()->json($cpus);
    }
    
    public function getGraphicsCards(Request $request) 
    {
        $graphicsCards = $this->getComponents(TarjetaGrafica::class, $request);
        return response()->json($graphicsCards);
    }
    
    public function getMotherboards(Request $request) 
    {
        $query = PlacasBase::query();

        if ($request->has('cpu_id')) {
            $cpu = Procesador::find($request->cpu_id);
            if ($cpu) {
                $query->where('socket', $cpu->socket);
            }
        }
    
        $motherboards = $this->getComponents($query, $request);
        return response()->json($motherboards);
    }
    
    public function getPowerSupplies(Request $request) 
    {
        $powerSupplies = $this->getComponents(FuenteAlimentacion::class, $request);
        return response()->json($powerSupplies);
    }
    
    public function getRams(Request $request) 
    {
        $rams = $this->getComponents(MemoriaRam::class, $request);
        return response()->json($rams);
    }
    
    public function getStorageDevices(Request $request) 
    {
        $storageDevices = $this->getComponents(Almacenamiento::class, $request);
        return response()->json($storageDevices);
    }
}
