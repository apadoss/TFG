<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;


class ConfiguracionesController extends Controller
{
    public function index() {
        $configuraciones = Configuracion::all();
        return view('configuraciones.index', compact('configuraciones'));
    }

    public function create(Request $request) {
        if ($request->input('type') == 'basic') {
            return view('configuraciones.create-basic');
        }

        return view('configuraciones.create-advanced');
    }
}
