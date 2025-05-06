@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="container-fluid bg-dark text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">PCompare</h1>
                <p class="fs-5">Encuentra componentes al mejor precio y crea la configuración perfecta para tu PC.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('configuraciones.index') }}" class="btn btn-primary btn-lg">Crear Configuración</a>
                    <a href="{{ route('ai-consultant.index') }}" class="btn btn-outline-light btn-lg">Consultar al Asesor IA</a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('images/hero.jpg') }}" alt="PC Components" class="img-fluid rounded shadow" />
            </div>
        </div>
    </div>
</div>

<!-- Categorías de Componentes -->
<div class="container py-5">
    <h2 class="text-center mb-5">Explora Componentes por Categoría</h2>
    <div class="row g-4">
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-cpu fs-1 text-primary mb-3"></i>
                    <h3 class="card-title">Procesadores</h3>
                    <p class="card-text">Compara los últimos procesadores Intel y AMD para encontrar el mejor rendimiento.</p>
                    <a href="{{ url('componentes/procesadores') }}" class="btn btn-outline-primary">Ver Procesadores</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-gpu-card fs-1 text-success mb-3"></i>
                    <h3 class="card-title">Tarjetas Gráficas</h3>
                    <p class="card-text">Encuentra las mejores GPU para gaming, diseño o trabajo profesional.</p>
                    <a href="{{ url('componentes/tarjetas-graficas') }}" class="btn btn-outline-success">Ver Tarjetas Gráficas</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-motherboard fs-1 text-danger mb-3"></i>
                    <h3 class="card-title">Placas Base</h3>
                    <p class="card-text">Compara placas base con diferentes chipsets y características.</p>
                    <a href="{{ url('componentes/placas-base') }}" class="btn btn-outline-danger">Ver Placas Base</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-device-hdd fs-1 text-info mb-3"></i>
                    <h3 class="card-title">Almacenamiento</h3>
                    <p class="card-text">SSD, HDD y soluciones NVMe para todas las necesidades de almacenamiento.</p>
                    <a href="{{ url('componentes/almacenamiento') }}" class="btn btn-outline-info">Ver Almacenamiento</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-memory fs-1 text-warning mb-3"></i>
                    <h3 class="card-title">Memoria RAM</h3>
                    <p class="card-text">Compara módulos de memoria por velocidad, latencia y compatibilidad.</p>
                    <a href="{{ url('componentes/ram') }}" class="btn btn-outline-warning">Ver Memoria RAM</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body text-center">
                    <i class="bi bi-lightning-charge fs-1 text-secondary mb-3"></i>
                    <h3 class="card-title">Fuentes de Alimentación</h3>
                    <p class="card-text">Encuentra la fuente ideal por potencia, certificación y eficiencia.</p>
                    <a href="{{ url('componentes/fuentes-alimentacion') }}" class="btn btn-outline-secondary">Ver Fuentes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Asesor IA -->
<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="{{ asset('images/asesor-ia.webp') }}" alt="AI Advisor" class="img-fluid rounded shadow" />
        </div>
        <div class="col-md-6">
            <h2>Asesor IA</h2>
            <p class="lead">¿No sabes qué componentes elegir? Nuestro asistente inteligente te ayudará a encontrar la configuración perfecta.</p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Recomendaciones basadas en tu presupuesto</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Consejos de compatibilidad entre componentes</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Sugerencias según tu uso: gaming, diseño, trabajo, etc.</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Análisis de relación calidad-precio</li>
            </ul>
            <a href="{{ url('asesor-ia') }}" class="btn btn-lg btn-primary mt-3">Consultar al Asesor IA</a>
        </div>
    </div>
</div>

<!-- Comparador de Portátiles -->
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Comparador de Portátiles</h2>
                <p class="lead">¿Buscas un portátil en lugar de un PC de escritorio? Nuestro comparador te ayuda a encontrar el modelo perfecto.</p>
                <p>Compara especificaciones, precios y opiniones de usuarios para elegir el mejor portátil para tus necesidades.</p>
                <a href="{{ url('componentes/portatiles') }}" class="btn btn-lg btn-primary mt-3">Comparar Portátiles</a>
            </div>
            <div class="col-md-6">
                <img src="/api/placeholder/500/350" alt="Laptop Comparison" class="img-fluid rounded shadow" />
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="container py-5 text-center">
    <div class="p-5 bg-primary text-white rounded shadow">
        <h2 class="display-5 fw-bold">¿Listo para empezar?</h2>
        <p class="lead mb-4">Crea una cuenta para guardar tus configuraciones y recibir alertas de precios</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Registrarse</a>
            <a href="{{ url('login') }}" class="btn btn-outline-light btn-lg">Iniciar Sesión</a>
        </div>
    </div>
</div>

<!-- CSS Personalizado para efectos de hover -->
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection