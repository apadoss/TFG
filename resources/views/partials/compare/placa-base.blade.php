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
    <td>Formato</td>
    <td>{{ $product1->format }}</td>
    <td>{{ $product2->format }}</td>
    <td>
        @if($product1->format == $product2->format)
            <span class="badge bg-secondary">Igual</span>
        @else
            <span class="badge bg-info">Diferente</span>
        @endif
    </td>
</tr>