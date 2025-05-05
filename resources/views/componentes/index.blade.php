@extends('layouts/app')
@section('content')
<div class="d-flex justify-content-end mb-3">
    <div class="btn-group" role="group" aria-label="Opciones de vista">
        <button id="cardViewBtn" class="btn btn-primary active" disabled>
            <i class="bi bi-grid-fill"></i>
        </button>
        <button id="listViewBtn" class="btn btn-secondary">
            <i class="bi bi-list-ul"></i>
        </button>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-4') }}
</div>

<!-- Vista de tarjetas (cards) - visible por defecto -->
<div id="cardsView" class="row">
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

<!-- Vista de lista (oculta por defecto) -->
<div id="listView" class="list-group" style="display: none;">
    @foreach ($products as $product)
    <div class="list-group-item d-flex align-items-center">
        <img src={{$product->image}} alt="{{$product->name}}" class="img-thumbnail mr-3" style="max-width: 100px;">
        <div class="flex-grow-1">
            <h5>{{$product->name}}</h5>
        </div>
        <a href={{route('componentes.view', [request()->segment(2), $product->id])}}>
            <button class="btn btn-primary">Detalles</button>
        </a>
    </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links('pagination::bootstrap-4') }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardViewBtn = document.getElementById('cardViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const cardsView = document.getElementById('cardsView');
        const listView = document.getElementById('listView');

        const urlParams = new URLSearchParams(window.location.search);
        const viewParam = urlParams.get('view');

        if (viewParam === 'list') {
            activateListView();
        } else {
            activateCardView();
        }

        // Función para cambiar a vista de tarjetas
        cardViewBtn.addEventListener('click', function() {
            activateCardView();
            updateUrlParam('cards');
        });

        // Función para cambiar a vista de lista
        listViewBtn.addEventListener('click', function() {
            activateListView();
            updateUrlParam('list');
        });

        // Función para activar vista de tarjetas
        function activateCardView() {
            cardsView.style.display = 'flex';
            listView.style.display = 'none';
            cardViewBtn.classList.add('active');
            cardViewBtn.classList.remove('btn-secondary');
            cardViewBtn.classList.add('btn-primary');
            cardViewBtn.disabled = true;
            listViewBtn.classList.remove('active');
            listViewBtn.classList.remove('btn-primary');
            listViewBtn.classList.add('btn-secondary');
            listViewBtn.disabled = false;
        }

        // Función para activar vista de lista
        function activateListView() {
            cardsView.style.display = 'none';
            listView.style.display = 'block';
            listViewBtn.classList.add('active');
            listViewBtn.classList.remove('btn-secondary');
            listViewBtn.classList.add('btn-primary');
            listViewBtn.disabled = true;
            cardViewBtn.classList.remove('active');
            cardViewBtn.classList.remove('btn-primary');
            cardViewBtn.classList.add('btn-secondary');
            cardViewBtn.disabled = false;
        }

        // Función para actualizar el parámetro view en la URL
        function updateUrlParam(viewType) {
            const url = new URL(window.location);
            url.searchParams.set('view', viewType);
            history.replaceState({}, '', url);

            updatePaginationLinks(viewType);
        }

        // Función para actualizar los enlaces de paginación
        function updatePaginationLinks(viewType) {
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                const linkUrl = new URL(link.href);
                linkUrl.searchParams.set('view', viewType);
                link.href = linkUrl.toString();
            });
        }

        if (viewParam) {
            updatePaginationLinks(viewParam);
        }
});
</script>
@endsection