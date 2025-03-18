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

    public function create() {
        return view('configuraciones.create');
    }
}
