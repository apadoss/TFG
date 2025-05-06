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

class ComponentesController extends Controller
{
    public function index(Request $request) {
        $segment = $request->segment(2);
        $name = $request->name;
        $products = [];
        switch ($segment) {
            case 'procesadores':
                $query = Procesador::query();
                break;
            case 'tarjetas-graficas':
                $query = TarjetaGrafica::query();
                break;
            case 'placas-base':
                $query = PlacasBase::query();
                break;
            case 'almacenamiento':
                $query = Almacenamiento::query();
                break;
            case 'ram':
                $query = MemoriaRam::query();
                break;
            case 'fuentes-alimentacion':
                $query = FuenteAlimentacion::query();
                break;
            case 'portatiles':
                $query = Portatil::query();
                break;
            default:
                abort(404);
        }
        
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        
        $products = $query->paginate(15)->appends($request->query());

        return view('componentes.index', compact('products'));
    }

    public function view(Request $request) {
        $segment = $request->segment(2);
        $product = null;

        switch ($segment) {
            case 'procesadores':
                $product = Procesador::find($request->id);
                break;
            case 'tarjetas-graficas':
                $product = TarjetaGrafica::find($request->id);
                break;
            case 'placas-base':
                $product = PlacasBase::find($request->id);
                break;
            case 'almacenamiento':
                $product = Almacenamiento::find($request->id);
                break;
            case 'ram':
                $product = MemoriaRam::find($request->id);
                break;
            case 'fuentes-alimentacion':
                $product = FuenteAlimentacion::find($request->id);
                break;
            case 'portatiles':
                $product = Portatil::find($request->id);
                break;
            default:
                abort(404);
            }

        return view('componentes.view', compact('product', 'request'));
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

    public function getCpus(Request $request) {
        $query = Procesador::select('id', 'name', 'image');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $cpus = $query->get();
        return response()->json($cpus);
    }

    public function getGraphicsCards(Request $request) {
        $query = TarjetaGrafica::select('id', 'name', 'image');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $graphicsCards = $query->get();
        return response()->json($graphicsCards);
    }

    public function getMotherboards(Request $request) {
        $query = PlacasBase::select('id', 'name', 'image');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $motherboards = $query->get();
        return response()->json($motherboards);
    }

    public function getPowerSupplies(Request $request) {
        $query = FuenteAlimentacion::select('id', 'name', 'image');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $powerSupplies = $query->get();
        return response()->json($powerSupplies);
    }

    public function getRams(Request $request) {
        $query = MemoriaRam::select('id', 'name', 'image');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $rams = $query->get();
        return response()->json($rams);
    }

    public function getStorageDevices(Request $request) {
        $query = Almacenamiento::select('id', 'name', 'image');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $storageDevices = $query->get();
        return response()->json($storageDevices);
    }
}
