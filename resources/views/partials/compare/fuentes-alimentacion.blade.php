<tr>
    <td>Potencia</td>
    <td>{{ $product1->power }} W</td>
    <td>{{ $product2->power }} W</td>
    <td>
        @php
            $diff = $product2->power - $product1->power;
            $percent = ($product1->power > 0) ? round(($diff / $product1->power) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-success">+{{ $percent }}% ({{ $diff }}W más)</span>
        @elseif($diff < 0)
            <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }}W menos)</span>
        @else
            <span class="badge bg-secondary">Igual</span>
        @endif
    </td>
</tr>
<tr>
    <td>Certificación</td>
    <td>{{ $product1->certification }}</td>
    <td>{{ $product2->certification }}</td>
    <td>
        @php
            // Ranking de certificaciones (mayor = mejor)
            $certRanking = [
                '80 Plus Titanium' => 6,
                '80 Plus Platinum' => 5,
                '80 Plus Gold' => 4,
                '80 Plus Silver' => 3,
                '80 Plus Bronze' => 2,
                '80 Plus' => 1,
                'Sin certificación' => 0,
            ];
            
            $cert1 = $product1->certification ?? 'Sin certificación';
            $cert2 = $product2->certification ?? 'Sin certificación';
            
            $rank1 = $certRanking[$cert1] ?? 0;
            $rank2 = $certRanking[$cert2] ?? 0;
        @endphp
        
        @if($rank1 == $rank2)
            <span class="badge bg-secondary">Igual</span>
        @elseif($rank2 > $rank1)
            <span class="badge bg-success">Mejor (mayor eficiencia)</span>
        @else
            <span class="badge bg-danger">Peor (menor eficiencia)</span>
        @endif
    </td>
</tr>