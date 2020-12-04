@extends('layout.layout')

@section('title', 'login')

@section('content')
    <div class="card" id="register-form">
        <div class="card-body m-3">
            <h1 class="card-title">Se connecter</h1>
            <div class="card text-center">
                <form action="/api/login" method="POST" class="m-5" id="login-form">
                    @csrf
                    <div class="form-group">
                        <label for="login-email">E-mail</label>
                        <input type="text" id="login-email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Mot de passe</label>
                        <input type="password" id="login-password" name="password" class="form-control" required>
                    </div>
                    <div class="btn-group align-items-center">
                        <a class="btn btn-secondary" role="button" href="/register">S'enregistrer</a>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
