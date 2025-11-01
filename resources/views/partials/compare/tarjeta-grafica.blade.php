<tr>
    <td>Memoria</td>
    <td>{{ $product1->vram }} GB</td>
    <td>{{ $product2->vram }} GB</td>
    <td>
        @php
            $diff = $product2->vram - $product1->vram;
            $percent = ($product1->vram > 0) ? round(($diff / $product1->vram) * 100, 1) : 0;
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
    <td>Tipo de memoria</td>
    <td>{{ $product1->mem_type }}</td>
    <td>{{ $product2->mem_type }}</td>
    <td>
        @php
            // Ranking de tipos de memoria de gráficas (mayor = mejor)
            $vramTypeRanking = [
                'GDDR7' => 7,
                'GDDR6X' => 6,
                'GDDR6' => 5,
                'GDDR5X' => 4,
                'GDDR5' => 3,
                'GDDR4' => 2,
                'GDDR3' => 1,
                'HBM3' => 8,   // High Bandwidth Memory (muy alta gama)
                'HBM2e' => 7,
                'HBM2' => 6,
                'HBM' => 5,
            ];
            
            $memType1 = $product1->mem_type ?? '';
            $memType2 = $product2->mem_type ?? '';
            
            $rank1 = $vramTypeRanking[$memType1] ?? 0;
            $rank2 = $vramTypeRanking[$memType2] ?? 0;
        @endphp
        
        @if($rank1 == $rank2)
            <span class="badge bg-secondary">Igual</span>
        @elseif($rank2 > $rank1)
            <span class="badge bg-success">Mejor (mayor ancho de banda)</span>
        @else
            <span class="badge bg-danger">Peor (menor ancho de banda)</span>
        @endif
    </td>
</tr>
<tr>
    <td>TDP</td>
    <td>{{ $product1->tdp }} W</td>
    <td>{{ $product2->tdp }} W</td>
    <td>
        @php
            $diff = $product2->tdp - $product1->tdp;
            $percent = ($product1->tdp > 0) ? round(($diff / $product1->tdp) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-warning">+{{ $percent }}% ({{ $diff }}W más consumo)</span>
        @elseif($diff < 0)
            <span class="badge bg-success">{{ $percent }}% ({{ abs($diff) }}W menos consumo)</span>
        @else
            <span class="badge bg-secondary">Igual</span>
        @endif
    </td>
</tr>