<?php

namespace App\Http\Controllers;

use App\Models\PriceHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PriceHistoryController extends Controller
{
    public function getMonthlyData(Request $request){
        $componentType = $request->query('component_type');
        $componentId = $request->query('component_id');

        if (!$componentType || !$componentId){
            return response()->json(['error' => 'Faltan parámetros'], 400);
        }

        $startDate = Carbon::now()->subMonths(9)->startOfMonth();

        $priceHistory = PriceHistory::where('component_type', $componentType)
                        ->where('component_id', $componentId)
                        ->where('created_at', '>=', $startDate)
                        ->orderBy('created_at')
                        ->get();
        
        $monthlyData = [];
        $months = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        foreach ($priceHistory as $record) {
            $createdAt = Carbon::parse($record->created_at);
            $monthKey = $months[$createdAt->month] . ' ' . $createdAt->year;

            if (!isset($monthlyData[$monthKey])) {
                $monthlyData[$monthKey] = [];
            }

            if (!isset($monthlyData[$monthKey][$record->vendor])) {
                $monthlyData[$monthKey][$record->vendor] = [];
            }

            $monthlyData[$monthKey][$record->vendor][] = [
                'day' => $createdAt->day,
                'price' => $record->price,
                'date' => $createdAt->format('Y-m-d')
            ];
        }

        return response()->json($monthlyData);
    } 
}
