<tr>
    <td>Capacidad</td>
    <td>{{ $product1->storage }} TB</td>
    <td>{{ $product2->storage }} TB</td>
    <td>
        @php
            $diff = $product2->storage - $product1->storage;
            $percent = ($product1->storage > 0) ? round(($diff / $product1->storage) * 100, 1) : 0;
        @endphp
        
        @if($diff > 0)
            <span class="badge bg-success">+{{ $percent }}% ({{ $diff }} TB más)</span>
        @elseif($diff < 0)
            <span class="badge bg-danger">{{ $percent }}% ({{ abs($diff) }} TB menos)</span>
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
        @if($product1->type == $product2->type)
            <span class="badge bg-secondary">Igual</span>
        @else
            <span class="badge bg-info">Diferente</span>
        @endif
    </td>
</tr>
<!-- Añadir más propiedades específicas de almacenamiento -->