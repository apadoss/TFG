@extends('layouts/app')

@section('content')
<div class="row">
    @foreach ($products as $product)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <img class="card-img-top" src={{$product->image}}>
                <div class="card-body">
                    <h5 class="card-title">{{$product->name}}</h5>
                    <a href={{route('componentes.view', [request()->segment(2), $product->id])}}>
                        <button class="btn btn-primary">Detalles</button>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
