@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="font-weight-bold mb-0">Iniciar Sesión</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logos/logo.png') }}" alt="PCompare Logo" class="img-fluid" style="max-height: 80px;">
                    </div>
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.authenticate') }}" class="needs-validation">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="correo@ejemplo.com">
                            <label for="email">Correo Electrónico</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
                            <label for="password">Contraseña</label>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Recordarme
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Iniciar Sesión
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <div class="small">
                        ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-decoration-none">¡Regístrate ahora!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection