<?php

namespace App\Http\Controllers;

use App\Models\componentes\Procesador;
use App\Models\componentes\TarjetaGrafica;
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
            default:
                abort(404);
            }
        return view('componentes.index', compact('products'));
    }

    public function view(Request $request) {
        $product = Procesador::find($request->id);

        return view('componentes.view', compact('product'));
    }
}
