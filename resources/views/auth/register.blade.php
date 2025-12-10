@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="font-weight-bold mb-0">Crear Cuenta</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logos/logo.png') }}" alt="PCompare Logo" class="img-fluid" style="max-height: 80px;">
                        <p class="mt-3">Únete a nuestra comunidad y obtén acceso a todas las funcionalidades</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.create') }}" class="needs-validation">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Tu nombre">
                                    <label for="name">Nombre</label>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" placeholder="Tus apellidos">
                                    <label for="last_name">Apellidos (opcional)</label>
                                    @error('last_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="correo@ejemplo.com">
                            <label for="email">Correo Electrónico</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Contraseña">
                                    <label for="password">Contraseña</label>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña">
                                    <label for="password-confirm">Confirmar Contraseña</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="terms">
                                Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">términos y condiciones</a> y la <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">política de privacidad</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" {{ old('newsletter') ? 'checked' : '' }}>
                            <label class="form-check-label" for="newsletter">
                                Me gustaría recibir ofertas y novedades sobre componentes (opcional)
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Registrarse
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <div class="small">
                        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">¿Por qué crear una cuenta?</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-bell-fill text-primary fs-4 me-2"></i>
                                <div>
                                    <h6 class="fw-bold">Alertas de precios</h6>
                                    <p class="small text-muted">Recibe notificaciones cuando los precios bajen en los componentes que te interesan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-save-fill text-primary fs-4 me-2"></i>
                                <div>
                                    <h6 class="fw-bold">Guarda configuraciones</h6>
                                    <p class="small text-muted">Crea y guarda tus configuraciones personalizadas para acceder a ellas en cualquier momento.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-graph-up text-primary fs-4 me-2"></i>
                                <div>
                                    <h6 class="fw-bold">Historial de precios</h6>
                                    <p class="small text-muted">Accede al historial completo de precios para tomar mejores decisiones de compra.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-chat-square-dots-fill text-primary fs-4 me-2"></i>
                                <div>
                                    <h6 class="fw-bold">Reseñas y valoraciones</h6>
                                    <p class="small text-muted">Comparte tu opinión sobre componentes y ayuda a otros usuarios.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Términos y Condiciones -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Términos y Condiciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Aceptación de los términos</h5>
                <p>Al registrarte en PCompare, aceptas todos los términos y condiciones aquí establecidos. Te recomendamos leer detenidamente este documento antes de proceder.</p>
                
                <h5>2. Uso del servicio</h5>
                <p>PCompare ofrece un servicio de comparación de precios de componentes informáticos y asesoramiento. Nos reservamos el derecho de modificar, suspender o discontinuar cualquier aspecto del servicio en cualquier momento.</p>
                
                <h5>3. Cuentas de usuario</h5>
                <p>Para utilizar ciertas funcionalidades de PCompare, deberás crear una cuenta. Eres responsable de mantener la confidencialidad de tu contraseña y de todas las actividades que ocurran bajo tu cuenta.</p>
                
                <h5>4. Propiedad intelectual</h5>
                <p>Todo el contenido presente en PCompare está protegido por leyes de propiedad intelectual. No está permitida la reproducción sin autorización expresa.</p>
                
                <h5>5. Modificaciones</h5>
                <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación en el sitio web.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Política de Privacidad -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">Política de Privacidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Recopilación de información</h5>
                <p>Recopilamos información personal como nombre, dirección de correo electrónico y preferencias cuando te registras en PCompare o participas en nuestras funcionalidades.</p>
                
                <h5>2. Uso de la información</h5>
                <p>Utilizamos la información recopilada para personalizar tu experiencia, mejorar nuestros servicios, procesamiento de transacciones y envío de información periódica sobre ofertas y novedades si has dado tu consentimiento.</p>
                
                <h5>3. Protección de la información</h5>
                <p>Implementamos una variedad de medidas de seguridad para mantener la seguridad de tu información personal. Los datos sensibles se transmiten encriptados y se almacenan con tecnologías de encriptación.</p>
                
                <h5>4. Divulgación a terceros</h5>
                <p>No vendemos, intercambiamos ni transferimos tu información personal identificable a terceros. Esto no incluye terceros de confianza que nos asisten en la operación de nuestro sitio web o la realización de nuestros servicios.</p>
                
                <h5>5. Derechos de acceso, rectificación y cancelación</h5>
                <p>Tienes derecho a acceder, rectificar y cancelar tus datos personales. Puedes ejercer estos derechos enviando un correo electrónico a privacidad@PCompare.com.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>
@endsection