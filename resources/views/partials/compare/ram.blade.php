<tr>
    <td>Capacidad</td>
    <td>{{ $product1->n_modules * $product1->module_capacity }} GB</td>
    <td>{{ $product2->n_modules * $product2->module_capacity }} GB</td>
    <td>
        @php
            $diff = ($product2->n_modules * $product2->module_capacity) - ($product1->n_modules * $product1->module_capacity);
            $percent = ($product1->n_modules * $product1->module_capacity > 0) ? round(($diff / ($product1->n_modules * $product1->module_capacity)) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} GB más)</span>
        @elseif($diff < 0)
            <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} GB menos)</span>
        @else
            <span class="badge bg-secondary">Igual</span>
        @endif
    </td>
</tr>
<tr>
    <td>Tipo</td>
    <td>{{ $product1->type }}</td>
    <td>{{ $product2->type }}</td>
    <td>
        @php
            // Ranking de tipos de memoria RAM (mayor = mejor/más moderna)
            $memTypeRanking = [
                'DDR5' => 5,
                'DDR4' => 4,
                'DDR3' => 3,
                'DDR2' => 2,
                'DDR' => 1,
            ];
            
            $type1 = $product1->type ?? '';
            $type2 = $product2->type ?? '';
            
            $rank1 = $memTypeRanking[$type1] ?? 0;
            $rank2 = $memTypeRanking[$type2] ?? 0;
        @endphp
        
        @if($rank1 == $rank2)
            <span class="badge bg-secondary">Igual</span>
        @elseif($rank2 > $rank1)
            <span class="badge bg-success">Mejor (generación más nueva)</span>
        @else
            <span class="badge bg-danger">Peor (generación más antigua)</span>
        @endif
    </td>
</tr>
<tr>
    <td>Velocidad</td>
    <td>{{ $product1->frequency }} MHz</td>
    <td>{{ $product2->frequency }} MHz</td>
    <td>
        @php
            $diff = $product2->frequency - $product1->frequency;
            $percent = ($product1->frequency > 0) ? round(($diff / $product1->frequency) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} MHz más)</span>
        @elseif($diff < 0)
            <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} MHz menos)</span>
        @else
            <span class="badge bg-secondary">Igual</span>
        @endif
    </td>
</tr>