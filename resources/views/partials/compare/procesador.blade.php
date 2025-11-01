<tr>
    <td>Velocidad de reloj</td>
    <td>{{ $product1->clock_speed }} GHz</td>
    <td>{{ $product2->clock_speed }} GHz</td>
    <td>
        @php
            $diff = $product2->clock_speed - $product1->clock_speed;
            $percent = ($product1->clock_speed > 0) ? round(($diff / $product1->clock_speed) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} GHz más)</span>
        @elseif($diff < 0)
            <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} GHz menos)</span>
        @else
            <span class="badge bg-secondary">Igual</span>
        @endif
    </td>
</tr>
<tr>
    <td>Nº de núcleos</td>
    <td>{{ $product1->n_cores }}</td>
    <td>{{ $product2->n_cores }}</td>
    <td>
        @php
            $diff = $product2->n_cores - $product1->n_cores;
            $percent = ($product1->n_cores > 0) ? round(($diff / $product1->n_cores) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} más)</span>
        @elseif($diff < 0)
            <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} menos)</span>
        @else
            <span class="badge bg-secondary">Igual</span>
        @endif
    </td>
</tr>
<tr>
    <td>Nº de hilos</td>
    <td>{{ $product1->n_threads }}</td>
    <td>{{ $product2->n_threads }}</td>
    <td>
        @php
            $diff = $product2->n_threads - $product1->n_threads;
            $percent = ($product1->n_threads > 0) ? round(($diff / $product1->n_threads) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} más)</span>
        @elseif($diff < 0)
            <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} menos)</span>
        @else
            <span class="badge bg-secondary">Igual</span>
        @endif
    </td>
</tr>
<tr>
    <td>Socket</td>
    <td>{{ $product1->socket }}</td>
    <td>{{ $product2->socket }}</td>
    <td>
        @if($product1->socket == $product2->socket)
            <span class="badge bg-secondary">Igual</span>
        @else
            <span class="badge bg-info">Diferente</span>
        @endif
    </td>
</tr>
<tr>
    <<td>TDP</td>
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