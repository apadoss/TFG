@extends('layouts/app')

@section('content')
<div class="container mt-4">
    <p class="h1">{{$product->name}}</p>
    <div class="row">
      <!-- Columna de la imagen -->
      <div class="col-md-6 text-center">
        <img src={{$product->image}} class="img-fluid" alt="Imagen">
      </div>
      <!-- Columna de la tabla -->
      <div class="col-md-6">
        <table class="table table-borderless align-middle">
          <thead class="table-light">
            <tr class="border-bottom">
              <th>Tienda</th>
              <th>Disponibilidad</th>
              <th>Precio</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-bottom">
              <td><img src="https://cdn.pccomponentes.com/img/logos/logo-pccomponentes.svg" alt="PC Componentes" class="img-fluid me-2" style="max-width: 100px;"></td>
              <td>En Stock</td>
              <td><b>{{$product->price}}€</b></td>
              <td><a href={{$product->url}}><button class="btn btn-success">Ir</button></a></td>
            </tr>
            <tr class="border-bottom">
                <td><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Amazon_logo.svg/1200px-Amazon_logo.svg.png" alt="Amazon" class="img-fluid me-2" style="max-width: 100px;"></td>
                <td>En Stock</td>
                <td></td>
                <td><a href="#"><button class="btn btn-success">Ir</button></a></td>
            </tr>
            <tr class="border-bottom">
                <td><img src="https://www.coolmod.com/images/logos/logo_coolmod.png" alt="Coolmod" class="img-fluid me-2" style="max-width: 100px;"></td>
                <td>En Stock</td>
                <td></td>
                <td><a href="#"><button class="btn btn-success">Ir</button></a></td>
            </tr>
            <tr class="border-bottom">
                <td><img src="https://www.neobyte.es/img/corporativo/neobyte_computers.png" alt="Neobyte" class="img-fluid me-2" style="max-width: 100px;"></td>
                <td>En Stock</td>
                <td></td>
                <td><a href="#"><button class="btn btn-success">Ir</button></a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="h1">Especificaciones</div>
    <div class="mt-4 mb-4">
      <a href="{{ route('componentes.compare', ['type' => $request->segment(2), 'product1' => $product->id]) }}" class="btn btn-primary">
        <i class="bi bi-arrow-left-right"></i>Comparar con otro componente
      </a>
    </div>
    <ul class="list-group">
        <li class="list-group-item">Nombre: {{$product->name}}</li>
        <li class="list-group-item">Marca: {{$product->brand}}</li>
        <li class="list-group-item">Velocidad de reloj: {{$product->clock_speed}} GHz</li>
        <li class="list-group-item">Nº de núcleos: {{$product->n_cores}}</li>
        <li class="list-group-item">Nº de hilos: {{$product->n_threads}}</li>
        <li class="list-group-item">Socket: {{$product->socket}}</li>
        <li class="list-group-item">TDP (Potencia de Diseño Térmico): {{$product->tdp}} W</li>
    </ul>
  </div>
@endsection
