@extends('admin.layout')

@section('title', 'Admin Login')

@section('home_link', './login') <!-- Désactive la sidebar -->
@section('endpoints_link', './login')
@section('logs_link', './login')
@section('logout_link', './login')

@section('content')
<div class="login-container" style="max-width:400px; margin:auto; background-color:#343a40; padding:1rem; border-radius:5px;">
  <h2 class="text-center mb-3">Login</h2>
  <form method="POST" action="login">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" name="username" class="form-control" id="username" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" id="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
  </form>
</div>
@endsection
