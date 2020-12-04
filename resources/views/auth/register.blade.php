@extends('layout.layout')

@section('title', 'register')

@section('content')
    <div class="card" id="register-form">
        <div class="card-body m-3">
            <h1 class="card-title">S'enregistrer</h1>
            <div class="card text-center">
                <form action="/register" method="POST" class="m-5">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Mot de passe (confirmation)</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                    <div>
                        <meter max="4" id="password-strength-meter" value=""></meter>
                        <p id="indic-password"></p>
                    </div>
                    <button type="submit" class="btn btn-primary">S'enregistrer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
