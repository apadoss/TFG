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
        
        $products = $query->get();

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

        return view('componentes.view', compact('product'));
    }
}
