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

    public function store(Request $request) {
        $configuracion = new Configuracion();

        $configuracion->user_id = auth()->user()->id;
        $configuracion->cpu_id = $request->input('procesador');
        $configuracion->gpu_id = $request->input('tarjeta_grafica');
        $configuracion->motherboard_id = $request->input('placa_base');
        $configuracion->storage_id = $request->input('almacenamiento');
        $configuracion->ram_id = $request->input('memoria_ram');
        $configuracion->power_supply_id = $request->input('fuente_de_alimentacion');

        $configuracion->save();

        return redirect(route('configuraciones.index'));
    }

    public function compare($id, $id2 = null){
        $config1 = Configuracion::with([
            'cpu',
            'graphic_card',
            'motherboard',
            'ram',
            'storage',
            'power_supply'
        ])->findOrFail($id);
        
        $config2 = null;
        if ($id2) {
            $config2 = Configuracion::with([
                'cpu',
                'graphic_card',
                'motherboard',
                'ram',
                'storage',
                'power_supply'
            ])->findOrFail($id2);
        }
        
        $allConfigs = Configuracion::where('id', '!=', $id)->get();
        
        return view('configuraciones.compare', compact('config1', 'config2', 'allConfigs'));
    } 
}
