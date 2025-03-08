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
        $products = [];
        switch ($segment) {
            case 'procesadores':
                $products = Procesador::all();
                break;
            case 'tarjetas-graficas':
                $products = TarjetaGrafica::all();
                break;
            case 'placas-base':
                $products = PlacasBase::all();
                break;
            case 'almacenamiento':
                $products = Almacenamiento::all();
                break;
            case 'ram':
                $products = MemoriaRam::all();
                break;
            case 'fuentes-alimentacion':
                $products = FuenteAlimentacion::all();
                break;
            case 'portatiles':
                $products = Portatil::all();
                break;
            default:
                abort(404);
            }
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
