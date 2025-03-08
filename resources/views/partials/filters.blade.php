@php
    $currentSegment = request()->segment(2);
@endphp

@switch($currentSegment)
    @case("procesadores")
        @include("partials.filters.procesador")
    @break

    @case("tarjetas-graficas")
        @include("partials.filters.tarjetas-graficas")
    @break

    @case("placas-base")
        @include("partials.filters.placas-base")
    @break

    @case("almacenamiento")
        @include("partials.filters.almacenamiento")
    @break
    
    @case("ram")
        @include("partials.filters.ram")
    @break
    
    @case("fuentes-alimentacion")
        @include("partials.filters.fuentes-alimentacion")
    @break
    
    @case("portatiles")
        @include("partials.filters.portatiles")
    @break
@endswitch